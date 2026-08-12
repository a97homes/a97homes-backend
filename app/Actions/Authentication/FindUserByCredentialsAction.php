<?php

namespace App\Actions\Authentication;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Builder;

class FindUserByCredentialsAction
{
    public function execute(?string $email, ?string $phone = null, ?string $countryCode = null): ?User
    {
        if (! $email && ! $phone) {
            return null;
        }

        return User::query()
            ->when($email, fn (Builder $query) => $query->where('email', $email))
            ->when(! $email && $phone, fn (Builder $query) => $query->where('phone', $phone)->where('country_code', $countryCode))
            ->first();
    }
}
