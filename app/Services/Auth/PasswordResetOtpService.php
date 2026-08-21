<?php

declare(strict_types=1);

namespace App\Services\Auth;

use App\Models\User\User;
use App\Notifications\PasswordResetOtpNotification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PasswordResetOtpService
{
    public const OTP_LENGTH = 6;

    public const EXPIRY_MINUTES = 15;

    /**
     * Generate a 6-digit OTP, persist its hash, and dispatch the email.
     * Returns the plain OTP only in non-production so tests/dev can assert it.
     */
    public function issueFor(User $user): string
    {
        $otp = $this->generateOtp();

        DB::table('password_reset_tokens')->updateOrInsert(
            ['email' => $user->email],
            [
                'email' => $user->email,
                'token' => Hash::make($otp),
                'created_at' => Carbon::now(),
            ],
        );

        $user->notify(new PasswordResetOtpNotification($otp, self::EXPIRY_MINUTES));

        return $otp;
    }

    /**
     * Verify the OTP for the given email. Returns true only if the token
     * exists, is still within the expiry window, and matches.
     */
    public function verify(string $email, string $otp): bool
    {
        $record = DB::table('password_reset_tokens')
            ->where('email', $email)
            ->first();

        if ($record === null) {
            return false;
        }

        if ($this->isExpired($record->created_at)) {
            return false;
        }

        return Hash::check($otp, $record->token);
    }

    public function consume(string $email): void
    {
        DB::table('password_reset_tokens')->where('email', $email)->delete();
    }

    private function isExpired(string $createdAt): bool
    {
        return Carbon::parse($createdAt)->addMinutes(self::EXPIRY_MINUTES)->isPast();
    }

    private function generateOtp(): string
    {
        return str_pad((string) random_int(0, 10 ** self::OTP_LENGTH - 1), self::OTP_LENGTH, '0', STR_PAD_LEFT);
    }
}
