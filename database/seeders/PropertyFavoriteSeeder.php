<?php

namespace Database\Seeders;

use App\Models\Property;
use App\Models\PropertyFavorite;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class PropertyFavoriteSeeder extends Seeder
{
    public function run(): void
    {
        $user = User::first();

        if (! $user) {
            $user = User::factory()->create([
                'name' => 'Test User',
                'email' => 'testuser@example.com',
            ]);
        }

        $properties = Property::query()
            ->inRandomOrder()
            ->limit(5)
            ->pluck('id');

        foreach ($properties as $propertyId) {
            PropertyFavorite::firstOrCreate([
                'user_id' => $user->id,
                'property_id' => $propertyId,
            ]);
        }
    }
}
