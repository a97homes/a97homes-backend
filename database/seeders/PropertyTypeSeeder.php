<?php

namespace Database\Seeders;

use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class PropertyTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $propertyTypes = [
            ['name' => ['en' => 'Apartment', 'ar' => 'شقة']],
            ['name' => ['en' => 'Villa', 'ar' => 'فيلا']],
            ['name' => ['en' => 'Studio', 'ar' => 'استوديو']],
            ['name' => ['en' => 'Office', 'ar' => 'مكتب']],
            ['name' => ['en' => 'Shop', 'ar' => 'محل']],
        ];

        foreach ($propertyTypes as $type) {
            PropertyType::create($type);
        }
    }
}
