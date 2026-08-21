<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Authentication\ResetPasswordRequest;
use App\Models\User\User;
use App\Services\Auth\PasswordResetOtpService;
use Illuminate\Http\JsonResponse;

class ResetPasswordController extends Controller
{
    public function __construct(private readonly PasswordResetOtpService $otpService) {}

    /**
     * Exchange a valid email + OTP for a password change. On success
     * every existing Sanctum token is revoked so the user must log in
     * again on every device.
     */
    public function reset(ResetPasswordRequest $request): JsonResponse
    {
        $email = $request->validated('email');
        $otp = $request->validated('otp');

        if (! $this->otpService->verify($email, $otp)) {
            return $this->unprocessable(__('auth.password_reset_otp_invalid'));
        }

        $user = User::query()->where('email', $email)->first();

        if ($user === null) {
            return $this->unprocessable(__('auth.password_reset_otp_invalid'));
        }

        $user->update(['password' => $request->validated('password')]);
        $user->tokens()->delete();
        $this->otpService->consume($email);

        return $this->ok(__('auth.password_reset_success'));
    }
}
