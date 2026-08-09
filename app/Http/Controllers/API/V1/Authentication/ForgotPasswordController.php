<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Authentication\ForgotPasswordRequest;
use App\Models\User\User;
use App\Services\Auth\PasswordResetOtpService;
use Illuminate\Http\JsonResponse;

class ForgotPasswordController extends Controller
{
    public function __construct(private readonly PasswordResetOtpService $otpService) {}

    /**
     * Issue a one-time password reset code to the provided email.
     *
     * The response is intentionally the same regardless of whether the
     * email is registered, to avoid account-enumeration leaks.
     */
    public function sendOtp(ForgotPasswordRequest $request): JsonResponse
    {
        $user = User::query()->where('email', $request->validated('email'))->first();

        if ($user !== null) {
            $this->otpService->issueFor($user);
        }

        return $this->ok(__('auth.password_reset_otp_sent'));
    }
}
