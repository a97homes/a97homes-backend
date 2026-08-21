<?php

namespace App\Models\User;

use App\Enums\Role\UserRoleEnum;

trait UserAccessor
{
    public function getUserLocale(): string
    {
        return $this->locale == null ? config('app.locale') : $this->locale;
    }

    /**
     * Admins and any back-office role (custom staff roles) may use the admin panel.
     */
    public function canAccessAdminPanel(): bool
    {
        return $this->roles()
            ->where('name', '!=', UserRoleEnum::USER->value)
            ->exists();
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(UserRoleEnum::ADMIN->value);
    }
}
