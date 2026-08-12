<?php

namespace App\Http\Requests\API\V1\Authentication;

use App\Models\User\User;

class AdminLoginRequest extends LoginRequest
{
    protected function allowsPanelAccess(User $user): bool
    {
        return $user->canAccessAdminPanel();
    }

    protected function accessDeniedMessageKey(): string
    {
        return 'auth.admin_access_denied';
    }
}
