<?php

namespace Database\Seeders;

use App\Models\Attribute;
use Illuminate\Database\Seeder;

class AttributesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $attributes = [
            [
                'name' => ['en' => 'Weight', 'ar' => 'الوزن'],
                'type' => 'number',
                'unit_id' => rand(1, 5),
            ],
            [
                'name' => ['en' => 'Color', 'ar' => 'اللون'],
                'type' => 'text',
                'unit_id' => rand(1, 5),
            ],
            [
                'name' => ['en' => 'Length', 'ar' => 'الطول'],
                'type' => 'number',
                'unit_id' => rand(1, 5),
            ],
            [
                'name' => ['en' => 'Material', 'ar' => 'المادة'],
                'type' => 'text',
                'unit_id' => rand(1, 5),
            ],
            [
                'name' => ['en' => 'Width', 'ar' => 'العرض'],
                'type' => 'number',
                'unit_id' => rand(1, 5),
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
}
