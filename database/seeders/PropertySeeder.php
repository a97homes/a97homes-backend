<?php

namespace Database\Seeders;

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
        $cairoId = City::where('name->en', 'Corniche')->value('id');
        $riyadhId = City::where('name->en', '6th of October')->value('id');

        $apartmentTypeId = PropertyType::where('name->en', 'Apartment')->value('id');
        $villaTypeId = PropertyType::where('name->en', 'Villa')->value('id');

        $properties = [
            [
                'name' => ['en' => 'Luxury Apartment', 'ar' => 'شقة فاخرة'],
                'city_id' => $cairoId,
                'property_type_id' => $apartmentTypeId,
                'value' => '2500000',
            ],
            [
                'name' => ['en' => 'Modern Villa', 'ar' => 'فيلا حديثة'],
                'city_id' => $riyadhId,
                'property_type_id' => $villaTypeId,
                'value' => '4500000',
            ],
        ];

        foreach ($properties as $property) {
            Property::create($property);
        }
    }
}
