<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\State;
use Illuminate\Database\Seeder;

class CitySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cairoId = State::where('name->en', 'Cairo')->value('id');
        $gizaId = State::where('name->en', 'Giza')->value('id');
        $riyadhId = State::where('name->en', 'Riyadh')->value('id');
        $jeddahId = State::where('name->en', 'Jeddah')->value('id');
        $dubaiId = State::where('name->en', 'Dubai')->value('id');
        $abudhabiId = State::where('name->en', 'Abu Dhabi')->value('id');

        $cities = [
            // cairo
            ['name' => ['en' => 'Nasr City', 'ar' => 'مدينة نصر'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Maadi', 'ar' => 'المعادي'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Heliopolis', 'ar' => 'هليوبوليس'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Zamalek', 'ar' => 'الزمالك'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Dokki', 'ar' => 'الدقي'], 'state_id' => $cairoId],
            ['name' => ['en' => 'Garden City', 'ar' => 'جاردن سيتي'], 'state_id' => $cairoId],

            // Giza
            ['name' => ['en' => '6th of October', 'ar' => '6 أكتوبر'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Sheikh Zayed', 'ar' => 'الشيخ زايد'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Haram', 'ar' => 'الهرم'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Mohandessin', 'ar' => 'المهندسين'], 'state_id' => $gizaId],
            ['name' => ['en' => 'Imbaba', 'ar' => 'إمبابة'], 'state_id' => $gizaId],

            ['name' => ['en' => 'Al Olaya', 'ar' => 'العليا'], 'state_id' => $riyadhId],
            ['name' => ['en' => 'Al Balad', 'ar' => 'البلد'], 'state_id' => $jeddahId],
            ['name' => ['en' => 'Business Bay', 'ar' => 'الخليج التجاري'], 'state_id' => $dubaiId],
            ['name' => ['en' => 'Corniche', 'ar' => 'الكورنيش'], 'state_id' => $abudhabiId],
        ];

        foreach ($cities as $city) {
            City::create($city);
        }
    }
}
