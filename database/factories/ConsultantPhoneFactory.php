<?php

namespace Database\Factories;

use App\Models\ConsultantPhone;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<ConsultantPhone>
 */
class ConsultantPhoneFactory extends Factory
{
    protected $model = ConsultantPhone::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'phone' => fake()->phoneNumber(),
        ];
    }
}
