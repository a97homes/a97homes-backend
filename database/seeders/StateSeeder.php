<?php

namespace Database\Seeders;

use App\Models\Country;
use App\Models\State;
use Illuminate\Database\Seeder;

class StateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $egyptId = Country::where('code', 'EG')->value('id');
        $saudiId = Country::where('code', 'SA')->value('id');
        $uaeId = Country::where('code', 'AE')->value('id');

        $states = [
            ['name' => ['en' => 'Cairo', 'ar' => 'القاهرة'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Giza', 'ar' => 'الجيزة'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Riyadh', 'ar' => 'الرياض'], 'country_id' => $saudiId],
            ['name' => ['en' => 'Jeddah', 'ar' => 'جدة'], 'country_id' => $saudiId],
            ['name' => ['en' => 'Dubai', 'ar' => 'دبي'], 'country_id' => $uaeId],
            ['name' => ['en' => 'Abu Dhabi', 'ar' => 'أبوظبي'], 'country_id' => $uaeId],
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
}
