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
                    'en' => 'Kilometer',
                    'ar' => 'كيلومتر',
                ],
                'symbol' => [
                    'en' => 'km',
                    'ar' => 'كم',
                ],
                'type' => 'length',
            ],
            [
                'name' => [
                    'en' => 'Centimeter',
                    'ar' => 'سنتيمتر',
                ],
                'symbol' => [
                    'en' => 'cm',
                    'ar' => 'سم',
                ],
                'type' => 'length',
            ],
            [
                'name' => [
                    'en' => 'Gram',
                    'ar' => 'جرام',
                ],
                'symbol' => [
                    'en' => 'g',
                    'ar' => 'جم',
                ],
                'type' => 'weight',
            ],
            [
                'name' => [
                    'en' => 'Kilogram',
                    'ar' => 'كيلوجرام',
                ],
                'symbol' => [
                    'en' => 'kg',
                    'ar' => 'كجم',
                ],
                'type' => 'weight',
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
