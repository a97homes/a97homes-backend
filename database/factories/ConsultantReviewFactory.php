<?php

namespace Database\Factories;

use App\Models\Consultant;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ConsultantReview>
 */
class ConsultantReviewFactory extends Factory
{
    public function definition(): array
    {
        return [
            'consultant_id' => Consultant::inRandomOrder()->value('id') ?? Consultant::factory(),
            'user_id' => User::inRandomOrder()->value('id') ?? User::factory(),
            'title' => fake()->sentence(4),
            'comment' => fake()->paragraph(3),
            'overall_rating' => fake()->numberBetween(3, 5),
            'local_knowledge_rating' => fake()->numberBetween(3, 5),
            'process_expertise_rating' => fake()->numberBetween(3, 5),
            'response_speed_rating' => fake()->numberBetween(3, 5),
            'negotiation_skills_rating' => fake()->numberBetween(3, 5),
        ];
    }
}
