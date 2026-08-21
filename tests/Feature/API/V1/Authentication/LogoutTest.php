<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Authentication;

use App\Enums\Role\UserRoleEnum;
use App\Enums\User\TokenAbilityEnum;
use App\Models\Role;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    private const USER_LOGOUT_URL = '/api/V1/logout';

    private const ADMIN_LOGOUT_URL = '/api/admin/V1/logout';

    protected function setUp(): void
    {
        parent::setUp();

        foreach (UserRoleEnum::cases() as $role) {
            Role::firstOrCreate(['name' => $role->value, 'guard_name' => 'web']);
        }
    }

    public function test_end_user_can_logout_from_the_user_endpoint_and_all_tokens_revoked(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRoleEnum::USER->value);
        $this->issueTokens($user);

        Sanctum::actingAs($user);

        $this->postJson(self::USER_LOGOUT_URL)->assertOk();

        $this->assertSame(0, $user->fresh()->tokens()->count());
    }

    public function test_user_without_any_role_can_logout_from_the_user_endpoint(): void
    {
        $user = User::factory()->create();
        $this->issueTokens($user);

        Sanctum::actingAs($user);

        $this->postJson(self::USER_LOGOUT_URL)->assertOk();

        $this->assertSame(0, $user->fresh()->tokens()->count());
    }

    public function test_admin_cannot_logout_from_the_user_endpoint(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(UserRoleEnum::ADMIN->value);
        $this->issueTokens($admin);

        Sanctum::actingAs($admin);

        $this->postJson(self::USER_LOGOUT_URL)
            ->assertForbidden()
            ->assertJsonPath('message.txt.0', __('auth.admin_logout_required'));

        $this->assertSame(2, $admin->fresh()->tokens()->count());
    }

    public function test_admin_can_logout_from_the_admin_endpoint_and_all_tokens_revoked(): void
    {
        $admin = User::factory()->create();
        $admin->assignRole(UserRoleEnum::ADMIN->value);
        $this->issueTokens($admin);

        Sanctum::actingAs($admin);

        $this->postJson(self::ADMIN_LOGOUT_URL)->assertOk();

        $this->assertSame(0, $admin->fresh()->tokens()->count());
    }

    public function test_staff_role_can_logout_from_the_admin_endpoint(): void
    {
        Role::firstOrCreate(['name' => 'moderator', 'guard_name' => 'web']);

        $staff = User::factory()->create();
        $staff->assignRole('moderator');
        $this->issueTokens($staff);

        Sanctum::actingAs($staff);

        $this->postJson(self::ADMIN_LOGOUT_URL)->assertOk();

        $this->assertSame(0, $staff->fresh()->tokens()->count());
    }

    public function test_end_user_cannot_logout_from_the_admin_endpoint(): void
    {
        $user = User::factory()->create();
        $user->assignRole(UserRoleEnum::USER->value);
        $this->issueTokens($user);

        Sanctum::actingAs($user);

        $this->postJson(self::ADMIN_LOGOUT_URL)
            ->assertForbidden()
            ->assertJsonPath('message.txt.0', __('auth.admin_access_denied'));

        $this->assertSame(2, $user->fresh()->tokens()->count());
    }

    public function test_unauthenticated_logout_returns_401_on_both_endpoints(): void
    {
        $this->postJson(self::USER_LOGOUT_URL)->assertUnauthorized();
        $this->postJson(self::ADMIN_LOGOUT_URL)->assertUnauthorized();
    }

    private function issueTokens(User $user): void
    {
        $user->createToken('access_token', [TokenAbilityEnum::ACCESS_API]);
        $user->createToken('refresh_token', [TokenAbilityEnum::ISSUE_ACCESS_TOKEN]);

        $this->assertSame(2, $user->tokens()->count());
    }
}
