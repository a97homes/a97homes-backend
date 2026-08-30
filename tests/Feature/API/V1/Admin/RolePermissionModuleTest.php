<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User\User;
use App\Permissions\PermissionRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Spatie\Permission\Guard;
use Spatie\Permission\PermissionRegistrar;
use Tests\TestCase;

class RolePermissionModuleTest extends TestCase
{
    use RefreshDatabase;

    private function guard(): string
    {
        return Guard::getDefaultName(User::class);
    }

    private function actingAsAdmin(): User
    {
        $admin = User::factory()->create();
        $admin->assignRole($this->role(UserRoleEnum::ADMIN->value));
        Sanctum::actingAs($admin);

        return $admin;
    }

    private function role(string $name): Role
    {
        $role = Role::firstOrCreate(['name' => $name, 'guard_name' => $this->guard()]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $role;
    }

    private function permission(string $name): Permission
    {
        $permission = Permission::firstOrCreate(['name' => $name, 'guard_name' => $this->guard()]);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        return $permission;
    }

    public function test_admin_can_list_roles_with_their_user_counts(): void
    {
        $this->actingAsAdmin();
        $editor = $this->role('editor');
        User::factory()->create()->assignRole($editor);

        $response = $this->getJson('/api/admin/V1/roles')->assertOk();

        $roles = collect($response->json('data.data'));

        $this->assertContains('editor', $roles->pluck('name')->all());
        $this->assertSame(1, $roles->firstWhere('name', 'editor')['user_have_role_count']);
    }

    public function test_admin_can_create_a_role(): void
    {
        $this->actingAsAdmin();

        $this->postJson('/api/admin/V1/roles', ['name' => 'editor'])->assertOk();

        $this->assertDatabaseHas('roles', ['name' => 'editor', 'guard_name' => $this->guard()]);
    }

    public function test_creating_a_duplicate_role_is_rejected(): void
    {
        $this->actingAsAdmin();
        $this->role('editor');

        $this->postJson('/api/admin/V1/roles', ['name' => 'editor'])->assertUnprocessable();
    }

    public function test_admin_can_rename_a_role(): void
    {
        $this->actingAsAdmin();
        $role = $this->role('editor');

        $this->putJson("/api/admin/V1/roles/{$role->id}", ['name' => 'reviewer'])->assertOk();

        $this->assertSame('reviewer', $role->fresh()->name);
    }

    public function test_the_admin_role_cannot_be_renamed(): void
    {
        $this->actingAsAdmin();
        $admin = $this->role(UserRoleEnum::ADMIN->value);

        $this->putJson("/api/admin/V1/roles/{$admin->id}", ['name' => 'super'])->assertUnprocessable();

        $this->assertSame(UserRoleEnum::ADMIN->value, $admin->fresh()->name);
    }

    public function test_admin_can_delete_an_unused_role(): void
    {
        $this->actingAsAdmin();
        $role = $this->role('editor');

        $this->deleteJson("/api/admin/V1/roles/{$role->id}")->assertOk();

        $this->assertNull($role->fresh());
    }

    public function test_a_role_that_still_has_users_cannot_be_deleted(): void
    {
        $this->actingAsAdmin();
        $role = $this->role('editor');
        User::factory()->create()->assignRole($role);

        $this->deleteJson("/api/admin/V1/roles/{$role->id}")->assertUnprocessable();

        $this->assertNotNull($role->fresh());
    }

    public function test_the_admin_role_cannot_be_deleted(): void
    {
        $this->actingAsAdmin();
        $admin = Role::query()->where('name', UserRoleEnum::ADMIN->value)->firstOrFail();

        $this->deleteJson("/api/admin/V1/roles/{$admin->id}")->assertUnprocessable();

        $this->assertNotNull($admin->fresh());
    }

    public function test_admin_can_assign_permissions_to_a_role(): void
    {
        $this->actingAsAdmin();
        $role = $this->role('editor');
        $permission = $this->permission(PermissionRegistry::ADMIN_ARTICLES_UPDATE);

        $this->postJson("/api/admin/V1/roles/{$role->id}/assign-permissions", [
            'permissions' => [$permission->id],
        ])->assertOk();

        $this->assertTrue($role->fresh()->hasPermissionTo(PermissionRegistry::ADMIN_ARTICLES_UPDATE));
    }

    public function test_assigning_permissions_keeps_the_ones_the_role_already_has(): void
    {
        $this->actingAsAdmin();
        $role = $this->role('editor');
        $role->givePermissionTo($this->permission(PermissionRegistry::ADMIN_ARTICLES_INDEX));
        $update = $this->permission(PermissionRegistry::ADMIN_ARTICLES_UPDATE);

        $this->postJson("/api/admin/V1/roles/{$role->id}/assign-permissions", [
            'permissions' => [$update->id],
        ])->assertOk();

        $this->assertEqualsCanonicalizing(
            [PermissionRegistry::ADMIN_ARTICLES_INDEX, PermissionRegistry::ADMIN_ARTICLES_UPDATE],
            $role->fresh()->permissions->pluck('name')->all()
        );
    }

    public function test_updating_role_permissions_replaces_them(): void
    {
        $this->actingAsAdmin();
        $role = $this->role('editor');
        $role->givePermissionTo($this->permission(PermissionRegistry::ADMIN_ARTICLES_INDEX));
        $update = $this->permission(PermissionRegistry::ADMIN_ARTICLES_UPDATE);

        $this->putJson("/api/admin/V1/roles/{$role->id}/update-permissions", [
            'permissions' => [$update->id],
        ])->assertOk();

        $this->assertSame(
            [PermissionRegistry::ADMIN_ARTICLES_UPDATE],
            $role->fresh()->permissions->pluck('name')->all()
        );
    }

    public function test_updating_role_permissions_with_an_empty_list_revokes_everything(): void
    {
        $this->actingAsAdmin();
        $role = $this->role('editor');
        $role->givePermissionTo($this->permission(PermissionRegistry::ADMIN_ARTICLES_INDEX));

        $this->putJson("/api/admin/V1/roles/{$role->id}/update-permissions", [
            'permissions' => [],
        ])->assertOk();

        $this->assertCount(0, $role->fresh()->permissions);
    }

    public function test_role_show_returns_the_permissions_granted_to_the_role(): void
    {
        $this->actingAsAdmin();
        $role = $this->role('editor');
        $role->givePermissionTo($this->permission(PermissionRegistry::ADMIN_ARTICLES_UPDATE));

        $response = $this->getJson("/api/admin/V1/roles/{$role->id}")->assertOk();

        $this->assertSame(
            [PermissionRegistry::ADMIN_ARTICLES_UPDATE],
            collect($response->json('data.permissions'))->pluck('name')->all()
        );
    }

    public function test_admin_can_list_permissions(): void
    {
        $this->actingAsAdmin();
        $this->permission(PermissionRegistry::ADMIN_ARTICLES_UPDATE);

        $response = $this->getJson('/api/admin/V1/permissions')->assertOk();

        $this->assertContains(
            PermissionRegistry::ADMIN_ARTICLES_UPDATE,
            collect($response->json('data.data'))->pluck('name')->all()
        );
    }

    public function test_a_permission_still_granted_to_a_role_cannot_be_deleted(): void
    {
        $this->actingAsAdmin();
        $permission = $this->permission(PermissionRegistry::ADMIN_ARTICLES_UPDATE);
        $this->role('editor')->givePermissionTo($permission);

        $this->deleteJson("/api/admin/V1/permissions/{$permission->id}")->assertUnprocessable();

        $this->assertNotNull($permission->fresh());
    }

    public function test_a_permission_still_held_by_a_user_cannot_be_deleted(): void
    {
        $this->actingAsAdmin();
        $permission = $this->permission(PermissionRegistry::ADMIN_ARTICLES_UPDATE);
        User::factory()->create()->givePermissionTo($permission);

        $this->deleteJson("/api/admin/V1/permissions/{$permission->id}")->assertUnprocessable();

        $this->assertNotNull($permission->fresh());
    }

    public function test_an_unused_permission_can_be_deleted(): void
    {
        $this->actingAsAdmin();
        $permission = $this->permission(PermissionRegistry::ADMIN_ARTICLES_UPDATE);

        $this->deleteJson("/api/admin/V1/permissions/{$permission->id}")->assertOk();

        $this->assertNull($permission->fresh());
    }

    public function test_a_user_without_the_role_permissions_is_forbidden(): void
    {
        $outsider = User::factory()->create();
        $outsider->assignRole($this->role(UserRoleEnum::USER->value));
        Sanctum::actingAs($outsider);

        $this->getJson('/api/admin/V1/roles')->assertForbidden();
        $this->getJson('/api/admin/V1/permissions')->assertForbidden();
        $this->postJson('/api/admin/V1/roles', ['name' => 'editor'])->assertForbidden();
    }

    public function test_a_user_holding_only_the_roles_index_permission_can_list_roles(): void
    {
        $manager = User::factory()->create();
        $manager->givePermissionTo($this->permission(PermissionRegistry::ADMIN_ROLES_INDEX));
        Sanctum::actingAs($manager);

        $this->getJson('/api/admin/V1/roles')->assertOk();
        $this->postJson('/api/admin/V1/roles', ['name' => 'editor'])->assertForbidden();
    }
}
