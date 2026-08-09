<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<City>
 */
class CityFactory extends Factory
{
    protected $model = City::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'en' => $this->faker->unique()->city(),
                'ar' => 'مدينة '.$this->faker->unique()->word(),
            ],
            'state_id' => State::factory(),
            'description' => [
                'en' => $this->faker->paragraph(3),
                'ar' => 'وصف مفصل للمنطقة يحتوي على أهم المعلومات.',
            ],
            'latitude' => $this->faker->latitude(22, 31),
            'longitude' => $this->faker->longitude(25, 35),
        ];
    }
}
