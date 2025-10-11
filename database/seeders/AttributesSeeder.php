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
        // Get unit IDs by type
        $lengthUnit = \App\Models\Unit::where('type', 'length')->first();
        $weightUnit = \App\Models\Unit::where('type', 'weight')->first();

        $attributes = [
            [
                'name' => ['en' => 'Weight', 'ar' => 'الوزن'],
                'type' => 'number',
                'unit_id' => $weightUnit?->id,
            ],
            [
                'name' => ['en' => 'Color', 'ar' => 'اللون'],
                'type' => 'text',
                'unit_id' => null, // Text attributes don't need units
            ],
            [
                'name' => ['en' => 'Length', 'ar' => 'الطول'],
                'type' => 'number',
                'unit_id' => $lengthUnit?->id,
            ],
            [
                'name' => ['en' => 'Material', 'ar' => 'المادة'],
                'type' => 'text',
                'unit_id' => null, // Text attributes don't need units
            ],
            [
                'name' => ['en' => 'Width', 'ar' => 'العرض'],
                'type' => 'number',
                'unit_id' => $lengthUnit?->id,
            ],
        ];

        foreach ($attributes as $attribute) {
            Attribute::create($attribute);
        }
    }
}
