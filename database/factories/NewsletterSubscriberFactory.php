<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\NewsletterStatusEnum;
use App\Models\NewsletterSubscriber;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<NewsletterSubscriber>
 */
class NewsletterSubscriberFactory extends Factory
{
    protected $model = NewsletterSubscriber::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'email' => $this->faker->unique()->safeEmail(),
            'locale' => $this->faker->randomElement(['ar', 'en']),
            'status' => NewsletterStatusEnum::Active,
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
            'unsubscribe_token' => NewsletterSubscriber::freshToken(),
            'source' => 'footer',
        ];
    }

    public function unsubscribed(): self
    {
        return $this->state(fn () => [
            'status' => NewsletterStatusEnum::Unsubscribed,
            'unsubscribed_at' => now(),
        ]);
    }
}
