<?php

namespace App\Models\User;

use App\Enums\User\TokenAbilityEnum;

trait UserAction
{
    public function tokenWithBearer(): string
    {
        /** @var User $this */
        $accessToken = $this->createToken('access_token', [TokenAbilityEnum::ACCESS_API], (int) config('sanctum.expiration'));

        return 'Bearer '.$accessToken->plainTextToken;
    }

    public function refreshTokenWithBearer(): string
    {
        $refreshToken = $this->createToken('refresh_token', [TokenAbilityEnum::ISSUE_ACCESS_TOKEN], config('sanctum.refresh_expiration'));

        return 'Bearer '.$refreshToken->plainTextToken;
    }
}
