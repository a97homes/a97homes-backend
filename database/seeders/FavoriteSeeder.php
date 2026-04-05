<?php

namespace Database\Seeders;

use App\Models\Compound;
use App\Models\Favorite;
use App\Models\User\User;
use Illuminate\Database\Seeder;

class FavoriteSeeder extends Seeder
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

        $compounds = Compound::whereNotNull('city_id')
            ->inRandomOrder()
            ->limit(4)
            ->pluck('id');

        foreach ($compounds as $compoundId) {
            Favorite::firstOrCreate([
                'user_id' => $user->id,
                'compound_id' => $compoundId,
            ]);
        }
    }
}
