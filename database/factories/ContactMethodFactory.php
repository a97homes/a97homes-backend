<?php

namespace Database\Factories;

use App\Enums\ContactMethodTypeEnum;
use App\Models\ContactMethod;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ContactMethod>
 */
class ContactMethodFactory extends Factory
{
    protected $model = ContactMethod::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'type' => ContactMethodTypeEnum::Phone,
            'country_code' => '+20',
            'number' => (string) $this->faker->unique()->numberBetween(1000000000, 1999999999),
            'is_primary' => false,
            'sort_order' => 0,
        ];
    }
}
