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
            'name' => fake()->company(),
            'about' => fake()->paragraph(3),
        ];
    }
}
