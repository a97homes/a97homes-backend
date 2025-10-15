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
            // مصر
            ['name' => ['en' => 'Cairo', 'ar' => 'القاهرة'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Giza', 'ar' => 'الجيزة'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Alexandria', 'ar' => 'الإسكندرية'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Luxor', 'ar' => 'الأقصر'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Aswan', 'ar' => 'أسوان'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Port Said', 'ar' => 'بورسعيد'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Suez', 'ar' => 'السويس'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Mansoura', 'ar' => 'المنصورة'], 'country_id' => $egyptId],

            // السعودية
            ['name' => ['en' => 'Riyadh', 'ar' => 'الرياض'], 'country_id' => $saudiId],
            ['name' => ['en' => 'Jeddah', 'ar' => 'جدة'], 'country_id' => $saudiId],
            ['name' => ['en' => 'Mecca', 'ar' => 'مكة المكرمة'], 'country_id' => $saudiId],

            // الإمارات
            ['name' => ['en' => 'Dubai', 'ar' => 'دبي'], 'country_id' => $uaeId],
            ['name' => ['en' => 'Abu Dhabi', 'ar' => 'أبوظبي'], 'country_id' => $uaeId],
            ['name' => ['en' => 'Sharjah', 'ar' => 'الشارقة'], 'country_id' => $uaeId],
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
}
