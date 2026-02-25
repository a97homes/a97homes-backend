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
            ['name' => ['en' => 'Alexandria', 'ar' => 'الإسكندرية'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Qalyubia', 'ar' => 'القليوبية'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Dakahlia', 'ar' => 'الدقهلية'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Sharqia', 'ar' => 'الشرقية'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Gharbia', 'ar' => 'الغربية'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Menofia', 'ar' => 'المنوفية'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Kafr El Sheikh', 'ar' => 'كفر الشيخ'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Damietta', 'ar' => 'دمياط'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Port Said', 'ar' => 'بورسعيد'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Ismailia', 'ar' => 'الإسماعيلية'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Suez', 'ar' => 'السويس'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Beheira', 'ar' => 'البحيرة'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Matrouh', 'ar' => 'مطروح'], 'country_id' => $egyptId],
            ['name' => ['en' => 'North Sinai', 'ar' => 'شمال سيناء'], 'country_id' => $egyptId],
            ['name' => ['en' => 'South Sinai', 'ar' => 'جنوب سيناء'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Red Sea', 'ar' => 'البحر الأحمر'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Fayoum', 'ar' => 'الفيوم'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Beni Suef', 'ar' => 'بني سويف'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Minya', 'ar' => 'المنيا'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Assiut', 'ar' => 'أسيوط'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Sohag', 'ar' => 'سوهاج'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Qena', 'ar' => 'قنا'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Luxor', 'ar' => 'الأقصر'], 'country_id' => $egyptId],
            ['name' => ['en' => 'Aswan', 'ar' => 'أسوان'], 'country_id' => $egyptId],
            ['name' => ['en' => 'New Valley', 'ar' => 'الوادي الجديد'], 'country_id' => $egyptId],

            ['name' => ['en' => 'Riyadh', 'ar' => 'الرياض'], 'country_id' => $saudiId],
            ['name' => ['en' => 'Jeddah', 'ar' => 'جدة'], 'country_id' => $saudiId],
            ['name' => ['en' => 'Mecca', 'ar' => 'مكة المكرمة'], 'country_id' => $saudiId],
            ['name' => ['en' => 'Dubai', 'ar' => 'دبي'], 'country_id' => $uaeId],
            ['name' => ['en' => 'Abu Dhabi', 'ar' => 'أبوظبي'], 'country_id' => $uaeId],
            ['name' => ['en' => 'Sharjah', 'ar' => 'الشارقة'], 'country_id' => $uaeId],
        ];

        foreach ($states as $state) {
            State::create($state);
        }
    }
}
