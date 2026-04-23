<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LogoutController extends Controller
{
    /**
     * Revoke every Sanctum token belonging to the authenticated user,
     * ending the session on every device that user is signed in on.
     */
    public function logout(Request $request): JsonResponse
    {
        /** @var User|null $user */
        $user = $request->user();

        if ($user === null) {
            return $this->unauthorized(__('auth.unauthorized'));
        }

        $user->tokens()->delete();

        return $this->ok(__('auth.logged_out'));
    }
}
