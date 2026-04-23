<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Authentication;

use App\Enums\User\TokenAbilityEnum;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class LogoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_authenticated_user_can_logout_and_all_tokens_revoked(): void
    {
        $user = User::factory()->create();
        $user->createToken('access_token', [TokenAbilityEnum::ACCESS_API]);
        $user->createToken('refresh_token', [TokenAbilityEnum::ISSUE_ACCESS_TOKEN]);

        $this->assertSame(2, $user->tokens()->count());

        Sanctum::actingAs($user);

        $this->postJson('/api/V1/logout')->assertOk();

        $this->assertSame(0, $user->fresh()->tokens()->count());
    }

    public function test_unauthenticated_logout_returns_401(): void
    {
        $this->postJson('/api/V1/logout')->assertUnauthorized();
    }
}
