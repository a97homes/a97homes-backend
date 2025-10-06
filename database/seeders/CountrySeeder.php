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
        ];

        foreach ($countries as $country) {
            Country::create($country);
        }
    }
}
