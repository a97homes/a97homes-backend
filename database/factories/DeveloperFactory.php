<?php

namespace Database\Factories;

use App\Models\Developer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Developer>
 */
class DeveloperFactory extends Factory
{
    protected $model = Developer::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'en' => fake()->company(),
                'ar' => 'شركة '.fake()->word(),
            ],
            'about' => [
                'en' => fake()->paragraph(3),
                'ar' => 'نبذة '.fake()->sentence(),
            ],
            'whatsapp' => fake()->e164PhoneNumber(),
            'phone' => fake()->e164PhoneNumber(),
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes): array => [
            'is_active' => false,
        ]);
    }
}
