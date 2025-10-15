<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\City;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ✅ نجيب الـ IDs مع التأكد إنهم موجودين فعلاً
        $nasrCityId = City::where('name->en', 'Nasr City')->value('id');
        $maadiId = City::where('name->en', 'Maadi')->value('id');
        $octoberId = City::where('name->en', '6th of October')->value('id');
        $businessBayId = City::where('name->en', 'Business Bay')->value('id');

        $apartmentTypeId = PropertyType::where('name->en', 'Apartment')->value('id');
        $villaTypeId = PropertyType::where('name->en', 'Villa')->value('id');
        $officeTypeId = PropertyType::where('name->en', 'Office')->value('id');
        $shopTypeId = PropertyType::where('name->en', 'Shop')->value('id');

        $properties = [
            [
                'name' => ['en' => 'Luxury Apartment in Nasr City', 'ar' => 'شقة فاخرة في مدينة نصر'],

                'property_type_id' => $apartmentTypeId,
                'city_id' => $nasrCityId,
                'status' => 'active',
            ],
            [
                'name' => ['en' => 'Modern Villa in 6th of October', 'ar' => 'فيلا حديثة في 6 أكتوبر'],

                'property_type_id' => $villaTypeId,
                'city_id' => $octoberId,
                'status' => 'active',
            ],
            [
                'name' => ['en' => 'Office Space in Maadi', 'ar' => 'مكتب إداري في المعادي'],
                'property_type_id' => $officeTypeId,
                'city_id' => $maadiId,
                'status' => 'pending',
            ],
            [
                'name' => ['en' => 'Retail Shop in Business Bay', 'ar' => 'محل تجاري في الخليج التجاري'],
                'property_type_id' => $shopTypeId,
                'city_id' => $businessBayId,
                'status' => 'blocked',
            ],
        ];

        foreach ($properties as $data) {
            $property = Property::create($data);

            if (class_exists(Attribute::class)) {
                $attributeIds = Attribute::inRandomOrder()->take(rand(3, 5))->pluck('id');
                $property->attributes()->attach($attributeIds);
            }
        }

    }
}
