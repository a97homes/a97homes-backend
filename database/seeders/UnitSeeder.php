<?php

namespace Database\Seeders;

use App\Models\Unit;
use Illuminate\Database\Seeder;

class UnitSeeder extends Seeder
{
    public function run(): void
    {
        $units = [
            [
                'name' => [
                    'en' => 'Meter',
                    'ar' => 'متر',
                ],
                'symbol' => [
                    'en' => 'm',
                    'ar' => 'م',
                ],
                'type' => 'length',
            ],
            [
                'name' => [
                    'en' => 'Square Meter',
                    'ar' => 'متر مربع',
                ],
                'symbol' => [
                    'en' => 'm²',
                    'ar' => 'م²',
                ],
                'type' => 'area',
            ],
            [
                'name' => [
                    'en' => 'Acre',
                    'ar' => 'فدان',
                ],
                'symbol' => [
                    'en' => 'acre',
                    'ar' => 'فدان',
                ],
                'type' => 'area',
            ],
            [
                'name' => [
                    'en' => 'Room',
                    'ar' => 'غرفة',
                ],
                'symbol' => [
                    'en' => 'room',
                    'ar' => 'غرفة',
                ],
                'type' => 'count',
            ],
            [
                'name' => [
                    'en' => 'Bathroom',
                    'ar' => 'حمام',
                ],
                'symbol' => [
                    'en' => 'bath',
                    'ar' => 'حمام',
                ],
                'type' => 'count',
            ],
            [
                'name' => [
                    'en' => 'Year',
                    'ar' => 'سنة',
                ],
                'symbol' => [
                    'en' => 'yr',
                    'ar' => 'سنة',
                ],
                'type' => 'time',
            ],
            [
                'name' => [
                    'en' => 'Price per Square Meter',
                    'ar' => 'السعر لكل متر مربع',
                ],
                'symbol' => [
                    'en' => 'EGP/m²',
                    'ar' => 'ج.م/م²',
                ],
                'type' => 'price',
            ],
        ];

        foreach ($units as $unit) {
            Unit::create([
                'name' => $unit['name'],
                'symbol' => $unit['symbol'],
                'type' => $unit['type'],
            ]);
        }
    }
}
