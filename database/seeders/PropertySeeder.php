<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\City;
use App\Models\Developer;
use App\Models\Phase;
use App\Models\Project;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertySeeder extends Seeder
{
    public function run(): void
    {
        // ==================== تأكد من وجود Developer ====================
        $developer = Developer::firstOrCreate(
            ['id' => 1],
            ['name' => 'Default Developer', 'about' => 'Developer for seeding purposes']
        );

        // ==================== تأكد من وجود Project ====================
        $project = Project::firstOrCreate(
            ['id' => 1],
            ['name' => 'Default Project', 'developer_id' => $developer->id]
        );

        // ==================== تأكد من وجود PropertyType ====================
        $apartmentTypeId = PropertyType::firstOrCreate(['name->en' => 'Apartment'], ['name' => ['en' => 'Apartment', 'ar' => 'شقة']])->id;

        // ==================== تأكد من وجود Phase ====================
        $phase = Phase::firstOrCreate(
            ['id' => 1],
            ['name' => 'Default Phase', 'project_id' => $project->id, 'property_type_id' => $apartmentTypeId]
        );

        // ==================== تأكد من وجود Cities ====================
        $nasrCityId = City::firstOrCreate(['name->en' => 'Nasr City'], ['name' => ['en' => 'Nasr City', 'ar' => 'مدينة نصر']])->id;

        // ==================== إنشاء Property ====================
        $properties = [
            [
                'name' => ['en' => 'Luxury Apartment in Nasr City', 'ar' => 'شقة فاخرة في مدينة نصر'],
                'property_type_id' => $apartmentTypeId,
                'city_id' => $nasrCityId,
                'status' => 'active',
                'address' => '123 Nasr City Street',
                'project_id' => $project->id,

                'latitude' => 30.0626,
                'longitude' => 31.2497,
            ],
        ];

        foreach ($properties as $data) {
            $property = Property::create($data);

            // إرفاق Attributes عشوائية إذا موجودة
            if (class_exists(Attribute::class)) {
                $attributeIds = Attribute::inRandomOrder()->take(rand(3, 5))->pluck('id');
                $property->attributes()->attach($attributeIds);
            }
        }
    }
}
