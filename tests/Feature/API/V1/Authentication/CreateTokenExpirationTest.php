<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Authentication;

use App\Enums\User\TokenAbilityEnum;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CreateTokenExpirationTest extends TestCase
{
    use RefreshDatabase;

    public function test_token_created_without_an_explicit_expiry_falls_back_to_the_configured_expiration(): void
    {
        config(['sanctum.expiration' => 10080]);

        $user = User::factory()->create();

        $user->createToken('access_token', [TokenAbilityEnum::ACCESS_API]);

        $this->assertEqualsWithDelta(
            now()->addMinutes(10080)->timestamp,
            $user->tokens()->sole()->expires_at->timestamp,
            5,
        );
    }

    public function test_token_created_without_an_explicit_expiry_is_not_already_expired(): void
    {
        config(['sanctum.expiration' => 10080]);

        $user = User::factory()->create();

        $user->createToken('access_token', [TokenAbilityEnum::ACCESS_API]);

        $this->assertTrue($user->tokens()->sole()->expires_at->isFuture());
    }

    public function test_explicit_expiry_in_minutes_overrides_the_configured_expiration(): void
    {
        config(['sanctum.expiration' => 10080]);

        $user = User::factory()->create();

        $user->createToken('access_token', [TokenAbilityEnum::ACCESS_API], 60);

        $this->assertEqualsWithDelta(
            now()->addMinutes(60)->timestamp,
            $user->tokens()->sole()->expires_at->timestamp,
            5,
        );
    }
}
