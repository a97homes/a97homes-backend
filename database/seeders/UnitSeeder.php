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
                'en' => 'Meter',
                'ar' => 'متر',
                'symbol_en' => 'm',
                'symbol_ar' => 'م',
                'type' => 'length',
            ],
            [
                'en' => 'Kilometer',
                'ar' => 'كيلومتر',
                'symbol_en' => 'km',
                'symbol_ar' => 'كم',
                'type' => 'length',
            ],
            [
                'en' => 'Centimeter',
                'ar' => 'سنتيمتر',
                'symbol_en' => 'cm',
                'symbol_ar' => 'سم',
                'type' => 'length',
            ],
            [
                'en' => 'Gram',
                'ar' => 'جرام',
                'symbol_en' => 'g',
                'symbol_ar' => 'جم',
                'type' => 'weight',
            ],
            [
                'en' => 'Kilogram',
                'ar' => 'كيلوجرام',
                'symbol_en' => 'kg',
                'symbol_ar' => 'كجم',
                'type' => 'weight',
            ],
            [
                'en' => 'Square Meter',
                'ar' => 'متر مربع',
                'symbol_en' => 'm²',
                'symbol_ar' => 'م²',
                'type' => 'area',
            ],
            [
                'en' => 'Acre',
                'ar' => 'فدان',
                'symbol_en' => 'acre',
                'symbol_ar' => 'فدان',
                'type' => 'area',
            ],
        ];

        foreach ($units as $unit) {
            Unit::create([
                'name' => [
                    'en' => $unit['en'],
                    'ar' => $unit['ar'],
                ],
                'symbol' => [
                    'en' => $unit['symbol_en'],
                    'ar' => $unit['symbol_ar'],
                ],
                'type' => $unit['type'],
            ]);
        }
    }
}
