<?php

namespace Database\Factories;

use App\Enums\SellUnitStatusEnum;
use App\Models\City;
use App\Models\PropertyType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\SellUnit>
 */
class SellUnitFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'phone' => fake()->e164PhoneNumber(),
            'city_id' => City::inRandomOrder()->value('id') ?? 1,
            'property_type_id' => PropertyType::inRandomOrder()->value('id') ?? 1,
            'bedrooms' => fake()->numberBetween(1, 6),
            'notes' => fake()->optional()->sentence(),
            'status' => SellUnitStatusEnum::PENDING,
        ];
    }

    public function approved(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SellUnitStatusEnum::APPROVED,
        ]);
    }

    public function rejected(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => SellUnitStatusEnum::REJECTED,
        ]);
    }
}
