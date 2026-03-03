<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class AssignAttributesToPropertiesSeeder extends Seeder
{
    public function run(): void
    {
        $propertyTypes = PropertyType::with('attributes')->get()->keyBy('id');
        $bedroomsAttr = Attribute::where('slug', 'number-of-bedrooms')->first();
        $bathroomsAttr = Attribute::where('slug', 'number-of-bathrooms')->first();

        $typeNames = PropertyType::all()->pluck('id')->mapWithKeys(function ($id) use ($propertyTypes) {
            $pt = $propertyTypes->get($id);

            return [$id => $pt ? $pt->getTranslation('name', 'en') : null];
        });

        $bedroomRanges = [
            'Apartment' => [1, 2, 3, 3, 4],
            'Villa' => [4, 5, 5, 6, 6],
            'Penthouse' => [3, 3, 4, 4, 5],
            'Townhouse' => [3, 3, 4, 4, 5],
        ];

        $bathroomRanges = [
            'Apartment' => [1, 1, 2, 2, 3],
            'Villa' => [3, 3, 4, 4, 5],
            'Penthouse' => [2, 2, 3, 3, 4],
            'Townhouse' => [2, 2, 3, 3, 3],
            'Studio' => [1],
            'Office' => [1, 1, 2],
            'Shop' => [1],
        ];

        $counter = 0;

        Property::query()
            ->select('id', 'property_type_id', 'price')
            ->whereDoesntHave('attributes', function ($q) use ($bedroomsAttr, $bathroomsAttr) {
                $q->whereIn('attributes.id', array_filter([$bedroomsAttr?->id, $bathroomsAttr?->id]))
                    ->whereNotNull('attribute_property.value');
            })
            ->chunk(100, function ($properties) use ($propertyTypes, $typeNames, $bedroomsAttr, $bathroomsAttr, $bedroomRanges, $bathroomRanges, &$counter) {
                foreach ($properties as $property) {
                    $propertyType = $propertyTypes->get($property->property_type_id);

                    if (! $propertyType) {
                        continue;
                    }

                    $typeName = $typeNames->get($property->property_type_id);

                    // Sync all attributes for the property type
                    $attributeIds = $propertyType->attributes->pluck('id')->toArray();

                    if (! empty($attributeIds)) {
                        $syncData = [];

                        foreach ($attributeIds as $attributeId) {
                            $syncData[$attributeId] = ['value' => null];
                        }

                        $property->attributes()->syncWithoutDetaching($syncData);
                    }

                    // Assign bedroom value based on property type
                    if ($bedroomsAttr && isset($bedroomRanges[$typeName])) {
                        $values = $bedroomRanges[$typeName];
                        $value = $values[array_rand($values)];
                        $property->attributes()->syncWithoutDetaching([
                            $bedroomsAttr->id => ['value' => (string) $value],
                        ]);
                    }

                    // Assign bathroom value based on property type
                    if ($bathroomsAttr && isset($bathroomRanges[$typeName])) {
                        $values = $bathroomRanges[$typeName];
                        $value = $values[array_rand($values)];
                        $property->attributes()->syncWithoutDetaching([
                            $bathroomsAttr->id => ['value' => (string) $value],
                        ]);
                    }

                    $counter++;
                }
            });

        $this->command->info("Assigned attributes to {$counter} properties.");
    }
}
