<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Country>
 */
class CountryFactory extends Factory
{
    protected $model = Country::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'en' => $this->faker->unique()->country(),
                'ar' => 'دولة '.$this->faker->unique()->word(),
            ],
            'code' => strtoupper($this->faker->unique()->lexify('??')),
            'phone_code' => '+'.$this->faker->numberBetween(1, 999),
        ];
    }
}
