<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $countries = [
            ['name' => ['en' => 'Egypt', 'ar' => 'مصر'], 'code' => 'EG'],
            ['name' => ['en' => 'Saudi Arabia', 'ar' => 'السعودية'], 'code' => 'SA'],
            ['name' => ['en' => 'United Arab Emirates', 'ar' => 'الإمارات'], 'code' => 'AE'],
            ['name' => ['en' => 'Kuwait', 'ar' => 'الكويت'], 'code' => 'KW'],
            ['name' => ['en' => 'Qatar', 'ar' => 'قطر'], 'code' => 'QA'],
            ['name' => ['en' => 'Bahrain', 'ar' => 'البحرين'], 'code' => 'BH'],
            ['name' => ['en' => 'Oman', 'ar' => 'عمان'], 'code' => 'OM'],
            ['name' => ['en' => 'Jordan', 'ar' => 'الأردن'], 'code' => 'JO'],
            ['name' => ['en' => 'Lebanon', 'ar' => 'لبنان'], 'code' => 'LB'],
            ['name' => ['en' => 'Morocco', 'ar' => 'المغرب'], 'code' => 'MA'],
            ['name' => ['en' => 'Tunisia', 'ar' => 'تونس'], 'code' => 'TN'],
            ['name' => ['en' => 'Algeria', 'ar' => 'الجزائر'], 'code' => 'DZ'],
            ['name' => ['en' => 'Iraq', 'ar' => 'العراق'], 'code' => 'IQ'],
            ['name' => ['en' => 'Syria', 'ar' => 'سوريا'], 'code' => 'SY'],
            ['name' => ['en' => 'Palestine', 'ar' => 'فلسطين'], 'code' => 'PS'],
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
