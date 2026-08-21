<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Seeder;

class CountrySeeder extends Seeder
{
    /**
     * Egypt is the only country the platform operates in. This seeder owns the
     * complete country reference dataset, so any other country is removed.
     */
    public function run(): void
    {
        $countries = [
            ['name' => ['en' => 'Egypt', 'ar' => 'مصر'], 'code' => 'EG', 'phone_code' => '+20'],
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
                $image = @file_get_contents("https://flagcdn.com/w320/{$code}.png");

                if ($image !== false) {
                    file_put_contents($filePath, $image);
                }
            }
        }

        Country::query()
            ->whereNotIn('code', array_column($countries, 'code'))
            ->delete();
    }
}
