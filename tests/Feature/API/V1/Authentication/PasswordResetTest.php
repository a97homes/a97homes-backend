<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Authentication;

use App\Enums\User\TokenAbilityEnum;
use App\Models\User\User;
use App\Notifications\PasswordResetOtpNotification;
use App\Services\Auth\PasswordResetOtpService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class PasswordResetTest extends TestCase
{
    use RefreshDatabase;

    public function test_forgot_password_issues_otp_to_existing_user(): void
    {
        Notification::fake();
        $user = User::factory()->create(['email' => 'user@example.com']);

        $this->postJson('/api/V1/forgot-password', ['email' => 'user@example.com'])
            ->assertOk();

        Notification::assertSentTo($user, PasswordResetOtpNotification::class);
        $this->assertDatabaseHas('password_reset_tokens', ['email' => 'user@example.com']);
    }

    public function test_forgot_password_returns_ok_for_unknown_email_without_leaking(): void
    {
        Notification::fake();

        $this->postJson('/api/V1/forgot-password', ['email' => 'nobody@example.com'])
            ->assertOk();

        Notification::assertNothingSent();
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'nobody@example.com']);
    }

    public function test_reset_password_with_valid_otp_updates_password_and_revokes_tokens(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $user->createToken('access_token', [TokenAbilityEnum::ACCESS_API]);
        $this->assertSame(1, $user->tokens()->count());

        $otp = app(PasswordResetOtpService::class)->issueFor($user);

        $this->postJson('/api/V1/reset-password', [
            'email' => 'user@example.com',
            'otp' => $otp,
            'password' => 'NewSecret123!',
            'password_confirmation' => 'NewSecret123!',
        ])->assertOk();

        $user->refresh();
        $this->assertTrue(Hash::check('NewSecret123!', $user->password));
        $this->assertSame(0, $user->tokens()->count());
        $this->assertDatabaseMissing('password_reset_tokens', ['email' => 'user@example.com']);
    }

    public function test_reset_password_with_wrong_otp_fails(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        app(PasswordResetOtpService::class)->issueFor($user);

        $this->postJson('/api/V1/reset-password', [
            'email' => 'user@example.com',
            'otp' => '000000',
            'password' => 'NewSecret123!',
            'password_confirmation' => 'NewSecret123!',
        ])->assertUnprocessable();
    }

    public function test_reset_password_with_expired_otp_fails(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $otp = app(PasswordResetOtpService::class)->issueFor($user);

        DB::table('password_reset_tokens')
            ->where('email', 'user@example.com')
            ->update(['created_at' => Carbon::now()->subMinutes(PasswordResetOtpService::EXPIRY_MINUTES + 1)]);

        $this->postJson('/api/V1/reset-password', [
            'email' => 'user@example.com',
            'otp' => $otp,
            'password' => 'NewSecret123!',
            'password_confirmation' => 'NewSecret123!',
        ])->assertUnprocessable();
    }

    public function test_reset_password_requires_confirmed_password(): void
    {
        $user = User::factory()->create(['email' => 'user@example.com']);
        $otp = app(PasswordResetOtpService::class)->issueFor($user);

        $this->postJson('/api/V1/reset-password', [
            'email' => 'user@example.com',
            'otp' => $otp,
            'password' => 'NewSecret123!',
            'password_confirmation' => 'Mismatch!',
        ])->assertUnprocessable();
    }
}
