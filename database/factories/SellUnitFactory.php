<?php

namespace Database\Factories;

use App\Enums\SellUnitStatusEnum;
use App\Models\PropertyType;
use App\Models\SubArea;
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
            'sub_area_id' => SubArea::inRandomOrder()->value('id') ?? 1,
            'property_type_id' => PropertyType::inRandomOrder()->value('id') ?? 1,
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
