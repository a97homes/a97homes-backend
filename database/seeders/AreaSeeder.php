<?php

namespace Database\Seeders;

use App\Models\Area;
use App\Models\Country;
use Illuminate\Database\Seeder;

class AreaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $egyptId = Country::where('code', 'EG')->value('id');

        $areas = [
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
        ];

        foreach ($areas as $area) {
            Area::create($area);
        }
    }
}
