<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\CompletionStatusEnum;
use App\Models\Compound;
use App\Models\Phase;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Phase>
 */
class PhaseFactory extends Factory
{
    protected $model = Phase::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'compound_id' => Compound::factory(),
            'name' => [
                'en' => 'Phase '.$this->faker->numberBetween(1, 10),
                'ar' => 'مرحلة '.$this->faker->numberBetween(1, 10),
            ],
            'description' => [
                'en' => $this->faker->sentence(),
                'ar' => 'وصف المرحلة.',
            ],
            'delivery_date' => $this->faker->dateTimeBetween('+6 months', '+5 years'),
            'completion_status' => CompletionStatusEnum::UnderConstruction,
            'sort_order' => $this->faker->numberBetween(0, 10),
        ];
    }
}
