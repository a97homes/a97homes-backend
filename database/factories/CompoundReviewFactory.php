<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Models\Compound;
use App\Models\CompoundReview;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<CompoundReview>
 */
class CompoundReviewFactory extends Factory
{
    protected $model = CompoundReview::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'compound_id' => Compound::factory(),
            'user_id' => User::factory(),
            'title' => $this->faker->sentence(4),
            'comment' => $this->faker->paragraph(),
            'overall_rating' => $this->faker->numberBetween(1, 5),
            'location_rating' => $this->faker->numberBetween(1, 5),
            'amenities_rating' => $this->faker->numberBetween(1, 5),
            'value_for_money_rating' => $this->faker->numberBetween(1, 5),
            'developer_reputation_rating' => $this->faker->numberBetween(1, 5),
        ];
    }
}
