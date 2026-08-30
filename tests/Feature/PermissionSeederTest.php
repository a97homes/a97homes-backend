<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\Enums\Role\UserRoleEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User\User;
use App\Permissions\PermissionScanner;
use Database\Seeders\PermissionSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Guard;
use Tests\TestCase;

class PermissionSeederTest extends TestCase
{
    use RefreshDatabase;

    private function guard(): string
    {
        return Guard::getDefaultName(User::class);
    }

    private function stalePermission(string $name = 'admin.legacy.index'): Permission
    {
        return Permission::create(['name' => $name, 'guard_name' => $this->guard()]);
    }

    public function test_it_creates_every_permission_the_code_guards_with(): void
    {
        $this->seed(PermissionSeeder::class);

        $stored = Permission::query()->pluck('name')->all();

        $this->assertEqualsCanonicalizing(PermissionScanner::all(), $stored);
    }

    public function test_it_grants_every_permission_to_the_admin_role(): void
    {
        $this->seed(PermissionSeeder::class);

        $admin = Role::query()->where('name', UserRoleEnum::ADMIN->value)->firstOrFail();

        $this->assertSame(
            Permission::query()->count(),
            $admin->permissions()->count()
        );
    }

    public function test_it_is_idempotent_and_keeps_the_existing_permission_rows(): void
    {
        $this->seed(PermissionSeeder::class);
        $before = Permission::query()->pluck('id', 'name')->all();

        $this->seed(PermissionSeeder::class);
        $after = Permission::query()->pluck('id', 'name')->all();

        $this->assertSame($before, $after);
    }

    public function test_it_deletes_a_stale_permission_nobody_uses(): void
    {
        $stale = $this->stalePermission();

        $this->seed(PermissionSeeder::class);

        $this->assertNull($stale->fresh());
    }

    public function test_it_keeps_a_stale_permission_assigned_directly_to_a_user(): void
    {
        $stale = $this->stalePermission();
        $user = User::factory()->create();
        $user->givePermissionTo($stale);

        $this->seed(PermissionSeeder::class);

        $this->assertNotNull($stale->fresh());
        $this->assertTrue($user->fresh()->hasDirectPermission($stale->name));
    }

    public function test_it_keeps_a_stale_permission_granted_to_a_non_admin_role(): void
    {
        $stale = $this->stalePermission();
        $role = Role::create(['name' => 'editor', 'guard_name' => $this->guard()]);
        $role->givePermissionTo($stale);

        $this->seed(PermissionSeeder::class);

        $this->assertNotNull($stale->fresh());
        $this->assertTrue($role->fresh()->hasPermissionTo($stale->name));
    }

    public function test_a_stale_permission_only_held_by_admin_is_dropped(): void
    {
        $stale = $this->stalePermission();
        $admin = Role::create(['name' => UserRoleEnum::ADMIN->value, 'guard_name' => $this->guard()]);
        $admin->givePermissionTo($stale);

        $this->seed(PermissionSeeder::class);

        $this->assertNull($stale->fresh());
    }

    public function test_it_preserves_the_permissions_and_roles_already_assigned_to_users(): void
    {
        $role = Role::create(['name' => 'editor', 'guard_name' => $this->guard()]);
        $granted = Permission::create([
            'name' => 'admin.articles.update',
            'guard_name' => $this->guard(),
        ]);
        $role->givePermissionTo($granted);

        $user = User::factory()->create();
        $user->assignRole($role);
        $user->givePermissionTo($granted);

        $this->seed(PermissionSeeder::class);

        $user = $user->fresh();

        $this->assertSame(['editor'], $user->getRoleNames()->all());
        $this->assertTrue($user->hasDirectPermission('admin.articles.update'));
        $this->assertTrue($role->fresh()->hasPermissionTo('admin.articles.update'));
    }

    public function test_it_adds_a_newly_guarded_permission_without_touching_the_others(): void
    {
        $this->seed(PermissionSeeder::class);

        $dropped = Permission::query()
            ->where('name', 'admin.articles.update')
            ->firstOrFail();
        $dropped->delete();

        $this->seed(PermissionSeeder::class);

        $this->assertDatabaseHas('permissions', [
            'name' => 'admin.articles.update',
            'guard_name' => $this->guard(),
        ]);
        $this->assertSame(count(PermissionScanner::all()), Permission::query()->count());
    }
}
