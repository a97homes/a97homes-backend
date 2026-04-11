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
            ['name' => ['en' => 'Egypt', 'ar' => 'مصر'], 'code' => 'EG', 'phone_code' => '+20'],
            ['name' => ['en' => 'United States', 'ar' => 'الولايات المتحدة'], 'code' => 'US', 'phone_code' => '+1'],
            ['name' => ['en' => 'Saudi Arabia', 'ar' => 'السعودية'], 'code' => 'SA', 'phone_code' => '+966'],
            ['name' => ['en' => 'United Arab Emirates', 'ar' => 'الإمارات'], 'code' => 'AE', 'phone_code' => '+971'],
            ['name' => ['en' => 'Kuwait', 'ar' => 'الكويت'], 'code' => 'KW', 'phone_code' => '+965'],
            ['name' => ['en' => 'Qatar', 'ar' => 'قطر'], 'code' => 'QA', 'phone_code' => '+974'],
            ['name' => ['en' => 'Bahrain', 'ar' => 'البحرين'], 'code' => 'BH', 'phone_code' => '+973'],
            ['name' => ['en' => 'Oman', 'ar' => 'عمان'], 'code' => 'OM', 'phone_code' => '+968'],
            ['name' => ['en' => 'Jordan', 'ar' => 'الأردن'], 'code' => 'JO', 'phone_code' => '+962'],
            ['name' => ['en' => 'Lebanon', 'ar' => 'لبنان'], 'code' => 'LB', 'phone_code' => '+961'],
            ['name' => ['en' => 'Morocco', 'ar' => 'المغرب'], 'code' => 'MA', 'phone_code' => '+212'],
            ['name' => ['en' => 'Tunisia', 'ar' => 'تونس'], 'code' => 'TN', 'phone_code' => '+216'],
            ['name' => ['en' => 'Algeria', 'ar' => 'الجزائر'], 'code' => 'DZ', 'phone_code' => '+213'],
            ['name' => ['en' => 'Iraq', 'ar' => 'العراق'], 'code' => 'IQ', 'phone_code' => '+964'],
            ['name' => ['en' => 'Syria', 'ar' => 'سوريا'], 'code' => 'SY', 'phone_code' => '+963'],
            ['name' => ['en' => 'Palestine', 'ar' => 'فلسطين'], 'code' => 'PS', 'phone_code' => '+970'],
        ];

        $flagsPath = public_path('flags');
        if (! is_dir($flagsPath)) {
            mkdir($flagsPath, 0755, true);
        }

        foreach ($countries as $countryData) {
            Country::updateOrCreate(
                ['code' => $countryData['code']],
                $countryData
            );

            $code = strtolower($countryData['code']);
            $filePath = $flagsPath."/{$code}.png";

            if (! file_exists($filePath)) {
                $image = file_get_contents("https://flagcdn.com/w320/{$code}.png");
                file_put_contents($filePath, $image);
            }
        }
    }
}
