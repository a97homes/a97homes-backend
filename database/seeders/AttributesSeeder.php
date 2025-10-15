<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Unit;
use Illuminate\Database\Seeder;

class AttributesSeeder extends Seeder
{
    public function run(): void
    {
        // نجيب الوحدات اللي هنستخدمها
        $areaUnit = Unit::where('type', 'area')->first();     // متر مربع
        $countUnit = Unit::where('type', 'count')->first();   // عدد (غرف / حمامات)
        $timeUnit = Unit::where('type', 'time')->first();     // سنة
        $priceUnit = Unit::where('type', 'price')->first();   // السعر لكل متر مربع

        $attributes = [
            [
                'name' => ['en' => 'Area', 'ar' => 'المساحة'],
                'type' => 'number',
                'unit_id' => $areaUnit?->id,
            ],
            [
                'name' => ['en' => 'Number of Rooms', 'ar' => 'عدد الغرف'],
                'type' => 'number',
                'unit_id' => $countUnit?->id,
            ],
            [
                'name' => ['en' => 'Number of Bathrooms', 'ar' => 'عدد الحمامات'],
                'type' => 'number',
                'unit_id' => $countUnit?->id,
            ],
            [
                'name' => ['en' => 'Floor', 'ar' => 'الدور'],
                'type' => 'number',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Finishing Type', 'ar' => 'نوع التشطيب'],
                'type' => 'text',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'View', 'ar' => 'الإطلالة'],
                'type' => 'text',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Building Age', 'ar' => 'عمر المبنى'],
                'type' => 'number',
                'unit_id' => $timeUnit?->id,
            ],
            [
                'name' => ['en' => 'Direction', 'ar' => 'الاتجاه'],
                'type' => 'text',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Balcony', 'ar' => 'شرفة'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Parking', 'ar' => 'موقف سيارات'],
                'type' => 'boolean',
                'unit_id' => null,
            ],
            [
                'name' => ['en' => 'Price per m²', 'ar' => 'السعر لكل متر مربع'],
                'type' => 'number',
                'unit_id' => $priceUnit?->id,
            ],
            [
                'name' => ['en' => 'Total Price', 'ar' => 'السعر الإجمالي'],
                'type' => 'number',
                'unit_id' => null,
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
}
