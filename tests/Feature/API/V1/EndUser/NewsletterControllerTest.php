<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Enums\NewsletterStatusEnum;
use App\Models\NewsletterSubscriber;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewsletterControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_subscribe_creates_active_subscriber(): void
    {
        $response = $this->postJson('/api/V1/newsletter/subscribe', [
            'email' => 'friend@example.com',
            'locale' => 'ar',
            'source' => 'footer',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.email', 'friend@example.com')
            ->assertJsonPath('data.status', 'active');

        $this->assertDatabaseHas('newsletter_subscribers', [
            'email' => 'friend@example.com',
            'status' => NewsletterStatusEnum::Active->value,
            'source' => 'footer',
        ]);
    }

    public function test_subscribe_is_idempotent_and_reactivates_unsubscribed(): void
    {
        $subscriber = NewsletterSubscriber::factory()->unsubscribed()->create([
            'email' => 'returning@example.com',
        ]);

        $this->postJson('/api/V1/newsletter/subscribe', [
            'email' => 'returning@example.com',
        ])->assertOk();

        $subscriber->refresh();

        $this->assertSame(NewsletterStatusEnum::Active, $subscriber->status);
        $this->assertNotNull($subscriber->subscribed_at);
        $this->assertNull($subscriber->unsubscribed_at);
        $this->assertSame(1, NewsletterSubscriber::query()->where('email', 'returning@example.com')->count());
    }

    public function test_subscribe_rejects_invalid_email(): void
    {
        $this->postJson('/api/V1/newsletter/subscribe', [
            'email' => 'not-an-email',
        ])->assertUnprocessable();
    }

    public function test_unsubscribe_with_valid_token_succeeds(): void
    {
        $subscriber = NewsletterSubscriber::factory()->create();

        $this->postJson('/api/V1/newsletter/unsubscribe', [
            'email' => $subscriber->email,
            'token' => $subscriber->unsubscribe_token,
        ])->assertOk();

        $subscriber->refresh();
        $this->assertSame(NewsletterStatusEnum::Unsubscribed, $subscriber->status);
        $this->assertNotNull($subscriber->unsubscribed_at);
    }

    public function test_unsubscribe_with_wrong_token_is_forbidden(): void
    {
        $subscriber = NewsletterSubscriber::factory()->create();

        $this->postJson('/api/V1/newsletter/unsubscribe', [
            'email' => $subscriber->email,
            'token' => 'definitely-not-the-token',
        ])->assertForbidden();

        $subscriber->refresh();
        $this->assertSame(NewsletterStatusEnum::Active, $subscriber->status);
    }

    public function test_unsubscribe_with_unknown_email_returns_404(): void
    {
        $this->postJson('/api/V1/newsletter/unsubscribe', [
            'email' => 'unknown@example.com',
            'token' => 'any',
        ])->assertNotFound();
    }
}
