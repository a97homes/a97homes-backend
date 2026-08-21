<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Authentication;

use App\Models\User\User;

class AdminLogoutController extends LogoutController
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
