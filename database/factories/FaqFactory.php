<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\City;
use App\Models\Faq;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Faq>
 */
class FaqFactory extends Factory
{
    protected $model = Faq::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'faqable_type' => (new City)->getMorphClass(),
            'faqable_id' => City::factory(),
            'question' => [
                'en' => $this->faker->sentence().'?',
                'ar' => 'ما هو '.$this->faker->word().'؟',
            ],
            'answer' => [
                'en' => $this->faker->paragraph(),
                'ar' => 'إجابة '.$this->faker->sentence(),
            ],
            'sort_order' => $this->faker->numberBetween(0, 10),
            'is_active' => true,
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
