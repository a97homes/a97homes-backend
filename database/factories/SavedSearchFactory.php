<?php

declare(strict_types=1);

namespace Database\Factories;

use App\Enums\SavedSearchTypeEnum;
use App\Models\SavedSearch;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<SavedSearch>
 */
class SavedSearchFactory extends Factory
{
    protected $model = SavedSearch::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->sentence(3),
            'type' => SavedSearchTypeEnum::Compound,
            'criteria' => [
                'filter' => [
                    'sub_area_id' => $this->faker->numberBetween(1, 10),
                ],
                'sort' => '-id',
            ],
            'notify_by_email' => false,
        ];
    }

    public function property(): self
    {
        return $this->state(fn () => ['type' => SavedSearchTypeEnum::Property]);
    }
}
