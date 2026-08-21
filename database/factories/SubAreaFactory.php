<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\SubArea;
use App\Models\Area;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SubArea>
 */
class SubAreaFactory extends Factory
{
    protected $model = SubArea::class;

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
            'area_id' => Area::factory(),
            'description' => [
                'en' => $this->faker->paragraph(3),
                'ar' => 'وصف مفصل للمنطقة يحتوي على أهم المعلومات.',
            ],
            'latitude' => $this->faker->latitude(22, 31),
            'longitude' => $this->faker->longitude(25, 35),
        ];
    }
}
