<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Compound;
use App\Models\Offer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Offer>
 */
class OfferFactory extends Factory
{
    protected $model = Offer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'compound_id' => Compound::factory(),
            'installment_years' => $this->faker->numberBetween(5, 10),
            'down_payment_percentage' => $this->faker->randomFloat(2, 5, 25),
            'monthly_payment' => $this->faker->numberBetween(10000, 80000),
            'description' => [
                'en' => $this->faker->sentence(),
                'ar' => 'عرض تمويلي مميز.',
            ],
            'is_active' => true,
        ];
    }

    public function inactive(): self
    {
        return $this->state(fn () => ['is_active' => false]);
    }
}
