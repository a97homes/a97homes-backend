<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Authentication;

use App\Enums\Role\UserRoleEnum;
use App\Models\Role;
use App\Models\User\User;
use App\Permissions\PermissionRegistry;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

class LoginTest extends TestCase
{
    use RefreshDatabase;

    private const USER_LOGIN_URL = '/api/V1/login';

    private const ADMIN_LOGIN_URL = '/api/admin/V1/login';

    protected function setUp(): void
    {
        parent::setUp();

        foreach (UserRoleEnum::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value, 'guard_name' => 'web']);
        }
    }

    public function test_end_user_can_login_from_the_user_endpoint(): void
    {
        $user = User::factory()->create(['password' => 'password']);
        $user->assignRole(UserRoleEnum::USER->value);

        $this->postJson(self::USER_LOGIN_URL, [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk()
            ->assertJsonPath('data.email', $user->email);
    }

    public function test_user_without_any_role_can_login_from_the_user_endpoint(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->postJson(self::USER_LOGIN_URL, [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk();
    }

    public function test_admin_cannot_login_from_the_user_endpoint(): void
    {
        $admin = User::factory()->create(['password' => 'password']);
        $admin->assignRole(UserRoleEnum::ADMIN->value);

        $this->postJson(self::USER_LOGIN_URL, [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertUnprocessable()
            ->assertJsonPath('message.email.0', __('auth.admin_login_required'));
    }

    public function test_admin_can_login_from_the_admin_endpoint_without_a_token(): void
    {
        $admin = User::factory()->create(['password' => 'password']);
        $admin->assignRole(UserRoleEnum::ADMIN->value);

        $this->postJson(self::ADMIN_LOGIN_URL, [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertOk()
            ->assertJsonPath('data.email', $admin->email)
            ->assertJsonPath('data.roles.0.name', UserRoleEnum::ADMIN->value);
    }

    public function test_staff_role_can_login_from_the_admin_endpoint(): void
    {
        Role::firstOrCreate(['name' => 'moderator', 'guard_name' => 'web']);

        $staff = User::factory()->create(['password' => 'password']);
        $staff->assignRole('moderator');

        $this->postJson(self::ADMIN_LOGIN_URL, [
            'email' => $staff->email,
            'password' => 'password',
        ])->assertOk();
    }

    public function test_admin_login_returns_roles_and_permissions_for_the_frontend(): void
    {
        $permission = Permission::firstOrCreate([
            'name' => PermissionRegistry::ADMIN_USERS_INDEX,
            'guard_name' => 'web',
        ]);

        $admin = User::factory()->create(['password' => 'password']);
        $admin->assignRole(UserRoleEnum::ADMIN->value);
        $admin->roles()->first()->givePermissionTo($permission);

        $this->postJson(self::ADMIN_LOGIN_URL, [
            'email' => $admin->email,
            'password' => 'password',
        ])->assertOk()
            ->assertJsonPath('data.roles.0.name', UserRoleEnum::ADMIN->value)
            ->assertJsonPath('data.permissions', [PermissionRegistry::ADMIN_USERS_INDEX]);
    }

    public function test_login_merges_direct_permissions_with_role_permissions(): void
    {
        $viaRole = Permission::firstOrCreate([
            'name' => PermissionRegistry::ADMIN_USERS_INDEX,
            'guard_name' => 'web',
        ]);
        $direct = Permission::firstOrCreate([
            'name' => PermissionRegistry::ADMIN_USERS_SHOW,
            'guard_name' => 'web',
        ]);

        $role = Role::firstOrCreate(['name' => 'moderator', 'guard_name' => 'web']);
        $role->givePermissionTo($viaRole);

        $staff = User::factory()->create(['password' => 'password']);
        $staff->assignRole('moderator');
        $staff->givePermissionTo($direct);

        $this->postJson(self::ADMIN_LOGIN_URL, [
            'email' => $staff->email,
            'password' => 'password',
        ])->assertOk()
            ->assertJsonPath('data.permissions', [
                PermissionRegistry::ADMIN_USERS_INDEX,
                PermissionRegistry::ADMIN_USERS_SHOW,
            ]);
    }

    public function test_end_user_login_returns_an_empty_permissions_list(): void
    {
        $user = User::factory()->create(['password' => 'password']);
        $user->assignRole(UserRoleEnum::USER->value);

        $this->postJson(self::USER_LOGIN_URL, [
            'email' => $user->email,
            'password' => 'password',
        ])->assertOk()
            ->assertJsonPath('data.roles.0.name', UserRoleEnum::USER->value)
            ->assertJsonPath('data.permissions', []);
    }

    public function test_end_user_cannot_login_from_the_admin_endpoint(): void
    {
        $user = User::factory()->create(['password' => 'password']);
        $user->assignRole(UserRoleEnum::USER->value);

        $this->postJson(self::ADMIN_LOGIN_URL, [
            'email' => $user->email,
            'password' => 'password',
        ])->assertUnprocessable()
            ->assertJsonPath('message.email.0', __('auth.admin_access_denied'));
    }

    public function test_roleless_user_cannot_login_from_the_admin_endpoint(): void
    {
        $user = User::factory()->create(['password' => 'password']);

        $this->postJson(self::ADMIN_LOGIN_URL, [
            'email' => $user->email,
            'password' => 'password',
        ])->assertUnprocessable();
    }

    public function test_wrong_password_fails_on_both_endpoints(): void
    {
        $admin = User::factory()->create(['password' => 'password']);
        $admin->assignRole(UserRoleEnum::ADMIN->value);

        $this->postJson(self::ADMIN_LOGIN_URL, [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ])->assertUnprocessable()
            ->assertJsonPath('message.password.0', __('auth.failed'));

        $this->postJson(self::USER_LOGIN_URL, [
            'email' => $admin->email,
            'password' => 'wrong-password',
        ])->assertUnprocessable()
            ->assertJsonPath('message.password.0', __('auth.failed'));
    }

    public function test_login_by_phone_works_on_both_endpoints(): void
    {
        $admin = User::factory()->create([
            'password' => 'password',
            'country_code' => '+20',
            'phone' => '1000000001',
        ]);
        $admin->assignRole(UserRoleEnum::ADMIN->value);

        $this->postJson(self::ADMIN_LOGIN_URL, [
            'country_code' => '+20',
            'phone' => '1000000001',
            'password' => 'password',
        ])->assertOk();

        $user = User::factory()->create([
            'password' => 'password',
            'country_code' => '+20',
            'phone' => '1000000002',
        ]);
        $user->assignRole(UserRoleEnum::USER->value);

        $this->postJson(self::USER_LOGIN_URL, [
            'country_code' => '+20',
            'phone' => '1000000002',
            'password' => 'password',
        ])->assertOk();
    }
}
