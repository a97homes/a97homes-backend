<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Area;
use App\Models\Country;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Area>
 */
class AreaFactory extends Factory
{
    protected $model = Area::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => [
                'en' => $this->faker->unique()->state(),
                'ar' => 'منطقة '.$this->faker->unique()->word(),
            ],
            'about' => [
                'en' => $this->faker->paragraph(3),
                'ar' => 'نبذة عن المنطقة تحتوي على أهم المعلومات.',
            ],
            'country_id' => Country::factory(),
        ];
    }
}
