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

class UserRolesAndPermissionsTest extends TestCase
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

    public function test_admin_can_assign_roles_to_a_user(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();
        $dataEntry = $this->role(UserRoleEnum::DATA_ENTRY->value);

        $this->postJson("/api/admin/V1/users/{$user->id}/assign-roles", [
            'roles' => [$dataEntry->id],
        ])->assertOk();

        $this->assertTrue($user->fresh()->hasRole(UserRoleEnum::DATA_ENTRY->value));
    }

    public function test_assign_roles_keeps_the_roles_the_user_already_has(): void
    {
        $this->actingAsAdmin();
        $editor = $this->role('editor');
        $reviewer = $this->role('reviewer');

        $user = User::factory()->create();
        $user->assignRole($editor);

        $this->postJson("/api/admin/V1/users/{$user->id}/assign-roles", [
            'roles' => [$reviewer->id],
        ])->assertOk();

        $this->assertEqualsCanonicalizing(
            ['editor', 'reviewer'],
            $user->fresh()->getRoleNames()->all()
        );
    }

    public function test_update_roles_replaces_the_user_roles(): void
    {
        $this->actingAsAdmin();
        $editor = $this->role('editor');
        $reviewer = $this->role('reviewer');

        $user = User::factory()->create();
        $user->assignRole($editor);

        $this->putJson("/api/admin/V1/users/{$user->id}/update-roles", [
            'roles' => [$reviewer->id],
        ])->assertOk();

        $this->assertSame(['reviewer'], $user->fresh()->getRoleNames()->all());
    }

    public function test_assigning_an_unknown_role_is_rejected(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();

        $this->postJson("/api/admin/V1/users/{$user->id}/assign-roles", [
            'roles' => [9999],
        ])->assertUnprocessable()
            ->assertJsonStructure(['message' => ['roles.0']]);
    }

    public function test_admin_can_assign_direct_permissions_to_a_user(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();
        $permission = $this->permission(PermissionRegistry::ADMIN_DEVELOPERS_INDEX);

        $this->postJson("/api/admin/V1/users/{$user->id}/assign-permissions", [
            'permissions' => [$permission->id],
        ])->assertOk();

        $this->assertTrue($user->fresh()->hasDirectPermission(PermissionRegistry::ADMIN_DEVELOPERS_INDEX));
    }

    public function test_assign_permissions_keeps_the_permissions_the_user_already_has(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();
        $user->givePermissionTo($this->permission(PermissionRegistry::ADMIN_DEVELOPERS_INDEX));
        $store = $this->permission(PermissionRegistry::ADMIN_DEVELOPERS_STORE);

        $this->postJson("/api/admin/V1/users/{$user->id}/assign-permissions", [
            'permissions' => [$store->id],
        ])->assertOk();

        $this->assertEqualsCanonicalizing(
            [PermissionRegistry::ADMIN_DEVELOPERS_INDEX, PermissionRegistry::ADMIN_DEVELOPERS_STORE],
            $user->fresh()->getDirectPermissions()->pluck('name')->all()
        );
    }

    public function test_update_permissions_replaces_the_user_direct_permissions(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();
        $user->givePermissionTo($this->permission(PermissionRegistry::ADMIN_DEVELOPERS_INDEX));
        $store = $this->permission(PermissionRegistry::ADMIN_DEVELOPERS_STORE);

        $this->putJson("/api/admin/V1/users/{$user->id}/update-permissions", [
            'permissions' => [$store->id],
        ])->assertOk();

        $this->assertSame(
            [PermissionRegistry::ADMIN_DEVELOPERS_STORE],
            $user->fresh()->getDirectPermissions()->pluck('name')->all()
        );
    }

    public function test_update_permissions_accepts_an_empty_list_to_revoke_everything(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();
        $user->givePermissionTo($this->permission(PermissionRegistry::ADMIN_DEVELOPERS_INDEX));

        $this->putJson("/api/admin/V1/users/{$user->id}/update-permissions", [
            'permissions' => [],
        ])->assertOk();

        $this->assertCount(0, $user->fresh()->getDirectPermissions());
    }

    public function test_updating_permissions_does_not_touch_permissions_inherited_from_roles(): void
    {
        $this->actingAsAdmin();
        $editor = $this->role('editor');
        $editor->givePermissionTo($this->permission(PermissionRegistry::ADMIN_ARTICLES_UPDATE));

        $user = User::factory()->create();
        $user->assignRole($editor);
        $user->givePermissionTo($this->permission(PermissionRegistry::ADMIN_DEVELOPERS_INDEX));

        $this->putJson("/api/admin/V1/users/{$user->id}/update-permissions", [
            'permissions' => [],
        ])->assertOk();

        $user = $user->fresh();

        $this->assertCount(0, $user->getDirectPermissions());
        $this->assertTrue($user->can(PermissionRegistry::ADMIN_ARTICLES_UPDATE));
    }

    public function test_assigning_an_unknown_permission_is_rejected(): void
    {
        $this->actingAsAdmin();
        $user = User::factory()->create();

        $this->postJson("/api/admin/V1/users/{$user->id}/assign-permissions", [
            'permissions' => [9999],
        ])->assertUnprocessable()
            ->assertJsonStructure(['message' => ['permissions.0']]);
    }

    public function test_user_show_returns_roles_and_effective_permissions(): void
    {
        $this->actingAsAdmin();
        $editor = $this->role('editor');
        $editor->givePermissionTo($this->permission(PermissionRegistry::ADMIN_ARTICLES_UPDATE));

        $user = User::factory()->create();
        $user->assignRole($editor);
        $user->givePermissionTo($this->permission(PermissionRegistry::ADMIN_DEVELOPERS_INDEX));

        $response = $this->getJson("/api/admin/V1/users/{$user->id}")->assertOk();

        $this->assertSame(['editor'], $response->json('data.roles'));
        $this->assertSame([PermissionRegistry::ADMIN_DEVELOPERS_INDEX], $response->json('data.direct_permissions'));
        $this->assertEqualsCanonicalizing(
            [PermissionRegistry::ADMIN_ARTICLES_UPDATE, PermissionRegistry::ADMIN_DEVELOPERS_INDEX],
            $response->json('data.permissions')
        );
    }

    public function test_a_user_holding_only_the_assign_permission_can_manage_permissions(): void
    {
        $manager = User::factory()->create();
        $manager->givePermissionTo($this->permission(PermissionRegistry::ADMIN_USERS_ASSIGN_PERMISSIONS));
        Sanctum::actingAs($manager);

        $target = User::factory()->create();
        $permission = $this->permission(PermissionRegistry::ADMIN_DEVELOPERS_INDEX);

        $this->postJson("/api/admin/V1/users/{$target->id}/assign-permissions", [
            'permissions' => [$permission->id],
        ])->assertOk();
    }

    public function test_a_user_without_the_permission_cannot_manage_permissions(): void
    {
        $outsider = User::factory()->create();
        $outsider->assignRole($this->role(UserRoleEnum::USER->value));
        Sanctum::actingAs($outsider);

        $target = User::factory()->create();
        $permission = $this->permission(PermissionRegistry::ADMIN_DEVELOPERS_INDEX);

        $this->postJson("/api/admin/V1/users/{$target->id}/assign-permissions", [
            'permissions' => [$permission->id],
        ])->assertForbidden();
    }

    public function test_a_user_without_the_permission_cannot_manage_roles(): void
    {
        $outsider = User::factory()->create();
        $outsider->assignRole($this->role(UserRoleEnum::USER->value));
        Sanctum::actingAs($outsider);

        $target = User::factory()->create();
        $role = $this->role('editor');

        $this->postJson("/api/admin/V1/users/{$target->id}/assign-roles", [
            'roles' => [$role->id],
        ])->assertForbidden();
    }

    public function test_guests_cannot_manage_roles_or_permissions(): void
    {
        $user = User::factory()->create();

        $this->postJson("/api/admin/V1/users/{$user->id}/assign-roles", ['roles' => []])
            ->assertUnauthorized();

        $this->postJson("/api/admin/V1/users/{$user->id}/assign-permissions", ['permissions' => []])
            ->assertUnauthorized();
    }
}
