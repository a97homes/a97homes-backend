<?php

declare(strict_types=1);

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PasswordResetOtpNotification extends Notification
{
    use Queueable;

    public function __construct(
        public readonly string $otp,
        public readonly int $expiresInMinutes,
    ) {}

    /**
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject(__('auth.password_reset_otp_subject'))
            ->greeting(__('auth.password_reset_otp_greeting'))
            ->line(__('auth.password_reset_otp_line'))
            ->line(__('auth.password_reset_otp_code', ['code' => $this->otp]))
            ->line(__('auth.password_reset_otp_expiry', ['minutes' => $this->expiresInMinutes]))
            ->line(__('auth.password_reset_otp_ignore'));
    }

    /**
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'otp' => $this->otp,
            'expires_in_minutes' => $this->expiresInMinutes,
        ];
    }
}
