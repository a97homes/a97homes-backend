<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CompletionStatusEnum;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\SubArea;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Compound>
 */
class CompoundFactory extends Factory
{
    protected $model = Compound::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => $this->faker->unique()->company(),
            'developer_id' => Developer::factory(),
            'sub_area_id' => SubArea::factory(),
            'completion_status' => CompletionStatusEnum::UnderConstruction,
            'description' => [
                'en' => $this->faker->paragraph(),
                'ar' => 'وصف تفصيلي للكمبوند.',
            ],
            'delivery_date' => $this->faker->dateTimeBetween('+1 year', '+5 years'),
            'is_featured' => false,
        ];
    }
}
