<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;

class AttributePropertyTypeSeeder extends Seeder
{
    public function run(): void
    {
        $propertyTypes = PropertyType::all()->keyBy(fn ($pt) => $pt->getTranslation('name', 'en'));
        $attributes = Attribute::all()->keyBy(fn ($a) => $a->getTranslation('name', 'en'));

        if ($propertyTypes->isEmpty() || $attributes->isEmpty()) {
            return;
        }

        $mapping = $this->getMapping();

        foreach ($mapping as $propertyTypeName => $attributeAssignments) {
            $propertyType = $propertyTypes->get($propertyTypeName);

            if (! $propertyType) {
                continue;
            }

            $syncData = [];

            foreach ($attributeAssignments as $assignment) {
                $attribute = $attributes->get($assignment['name']);

                if (! $attribute) {
                    continue;
                }

                $syncData[$attribute->id] = ['is_required' => $assignment['required']];
            }

            $propertyType->attributes()->syncWithoutDetaching($syncData);
        }
    }

    /**
     * @return array<string, array<int, array{name: string, required: bool}>>
     */
    private function getMapping(): array
    {
        $shared = [
            ['name' => 'Area', 'required' => true],
            ['name' => 'Total Price', 'required' => true],
            ['name' => 'Finishing Type', 'required' => true],
            ['name' => 'Payment Method', 'required' => true],
            ['name' => 'Ownership Type', 'required' => false],
            ['name' => 'Property Condition', 'required' => false],
            ['name' => 'View', 'required' => false],
            ['name' => 'Direction', 'required' => false],
            ['name' => 'Price per m²', 'required' => false],
            ['name' => 'Down Payment', 'required' => false],
            ['name' => 'Monthly Installment', 'required' => false],
            ['name' => 'Installment Period', 'required' => false],
            ['name' => 'Delivery Date', 'required' => false],
            ['name' => 'Electricity', 'required' => false],
            ['name' => 'Water Supply', 'required' => false],
            ['name' => 'Natural Gas', 'required' => false],
            ['name' => 'Internet', 'required' => false],
        ];

        $residentialCommon = [
            ...$shared,
            ['name' => 'Number of Bedrooms', 'required' => true],
            ['name' => 'Number of Bathrooms', 'required' => true],
            ['name' => 'Number of Rooms', 'required' => false],
            ['name' => 'Number of Living Rooms', 'required' => false],
            ['name' => 'Number of Kitchens', 'required' => false],
            ['name' => 'Furnishing Status', 'required' => true],
            ['name' => 'Flooring Type', 'required' => false],
            ['name' => 'Heating Type', 'required' => false],
            ['name' => 'Cooling Type', 'required' => false],
            ['name' => 'Balcony', 'required' => false],
            ['name' => 'Parking', 'required' => false],
            ['name' => 'Elevator', 'required' => false],
            ['name' => 'Security', 'required' => false],
            ['name' => 'Air Conditioning', 'required' => false],
            ['name' => 'Central Gas', 'required' => false],
            ['name' => 'Intercom', 'required' => false],
            ['name' => 'Satellite/Cable TV', 'required' => false],
            ['name' => 'Fire System', 'required' => false],
            ['name' => 'CCTV', 'required' => false],
            ['name' => 'Smart Home', 'required' => false],
            ['name' => "Maid's Room", 'required' => false],
            ['name' => "Driver's Room", 'required' => false],
            ['name' => 'Storage Room', 'required' => false],
            ['name' => 'Laundry Room', 'required' => false],
        ];

        return [
            // ===========================
            // APARTMENT
            // ===========================
            'Apartment' => [
                ...$residentialCommon,
                ['name' => 'Built-up Area', 'required' => false],
                ['name' => 'Floor Number', 'required' => true],
                ['name' => 'Number of Floors', 'required' => false],
                ['name' => 'Building Age', 'required' => false],
                ['name' => 'Ceiling Height', 'required' => false],
            ],

            // ===========================
            // VILLA
            // ===========================
            'Villa' => [
                ...$residentialCommon,
                ['name' => 'Land Area', 'required' => true],
                ['name' => 'Built-up Area', 'required' => false],
                ['name' => 'Garden Area', 'required' => false],
                ['name' => 'Roof Area', 'required' => false],
                ['name' => 'Number of Floors', 'required' => true],
                ['name' => 'Number of Parking Spaces', 'required' => false],
                ['name' => 'Street Width', 'required' => false],
                ['name' => 'Private Garden', 'required' => false],
                ['name' => 'Private Pool', 'required' => false],
                ['name' => 'Jacuzzi', 'required' => false],
                ['name' => 'Roof Access', 'required' => false],
                ['name' => 'Terrace', 'required' => false],
                ['name' => 'Garden', 'required' => false],
                ['name' => 'Swimming Pool', 'required' => false],
                ['name' => 'Gym', 'required' => false],
                ['name' => 'Solar Energy', 'required' => false],
                ['name' => 'Doorman', 'required' => false],
            ],

            // ===========================
            // STUDIO
            // ===========================
            'Studio' => [
                ...$shared,
                ['name' => 'Number of Bathrooms', 'required' => true],
                ['name' => 'Furnishing Status', 'required' => true],
                ['name' => 'Floor Number', 'required' => true],
                ['name' => 'Flooring Type', 'required' => false],
                ['name' => 'Cooling Type', 'required' => false],
                ['name' => 'Balcony', 'required' => false],
                ['name' => 'Elevator', 'required' => false],
                ['name' => 'Security', 'required' => false],
                ['name' => 'Air Conditioning', 'required' => false],
                ['name' => 'Building Age', 'required' => false],
            ],

            // ===========================
            // PENTHOUSE
            // ===========================
            'Penthouse' => [
                ...$residentialCommon,
                ['name' => 'Roof Area', 'required' => false],
                ['name' => 'Floor Number', 'required' => true],
                ['name' => 'Number of Floors', 'required' => false],
                ['name' => 'Private Pool', 'required' => false],
                ['name' => 'Jacuzzi', 'required' => false],
                ['name' => 'Roof Access', 'required' => false],
                ['name' => 'Terrace', 'required' => false],
                ['name' => 'Swimming Pool', 'required' => false],
                ['name' => 'Gym', 'required' => false],
            ],

            // ===========================
            // TOWNHOUSE
            // ===========================
            'Townhouse' => [
                ...$residentialCommon,
                ['name' => 'Land Area', 'required' => false],
                ['name' => 'Built-up Area', 'required' => false],
                ['name' => 'Garden Area', 'required' => false],
                ['name' => 'Number of Floors', 'required' => true],
                ['name' => 'Number of Parking Spaces', 'required' => false],
                ['name' => 'Private Garden', 'required' => false],
                ['name' => 'Terrace', 'required' => false],
                ['name' => 'Garden', 'required' => false],
            ],

            // ===========================
            // COMPOUND
            // ===========================
            'Compound' => [
                ['name' => 'Area', 'required' => true],
                ['name' => 'Total Price', 'required' => false],
                ['name' => 'Number of Units', 'required' => false],
                ['name' => 'Payment Method', 'required' => false],
                ['name' => 'Delivery Date', 'required' => false],
                ['name' => 'Security', 'required' => false],
                ['name' => 'Swimming Pool', 'required' => false],
                ['name' => 'Gym', 'required' => false],
                ['name' => 'Garden', 'required' => false],
                ['name' => 'Clubhouse', 'required' => false],
                ['name' => 'Kids Area', 'required' => false],
                ['name' => 'Commercial Area', 'required' => false],
                ['name' => 'Mosque', 'required' => false],
                ['name' => 'Jogging Track', 'required' => false],
                ['name' => 'Sports Courts', 'required' => false],
                ['name' => 'Spa', 'required' => false],
                ['name' => 'Business Center', 'required' => false],
                ['name' => 'Medical Center', 'required' => false],
                ['name' => 'School Nearby', 'required' => false],
                ['name' => 'Hospital Nearby', 'required' => false],
                ['name' => 'Mall Nearby', 'required' => false],
                ['name' => 'Metro Nearby', 'required' => false],
                ['name' => 'Highway Access', 'required' => false],
                ['name' => 'Public Transport Nearby', 'required' => false],
                ['name' => 'CCTV', 'required' => false],
                ['name' => 'Fire System', 'required' => false],
            ],

            // ===========================
            // OFFICE
            // ===========================
            'Office' => [
                ...$shared,
                ['name' => 'Floor Number', 'required' => true],
                ['name' => 'Number of Rooms', 'required' => false],
                ['name' => 'Number of Bathrooms', 'required' => false],
                ['name' => 'Furnishing Status', 'required' => false],
                ['name' => 'Cooling Type', 'required' => false],
                ['name' => 'Flooring Type', 'required' => false],
                ['name' => 'Elevator', 'required' => false],
                ['name' => 'Security', 'required' => false],
                ['name' => 'Parking', 'required' => false],
                ['name' => 'Air Conditioning', 'required' => false],
                ['name' => 'Fire System', 'required' => false],
                ['name' => 'CCTV', 'required' => false],
                ['name' => 'Intercom', 'required' => false],
                ['name' => 'Building Age', 'required' => false],
                ['name' => 'Ceiling Height', 'required' => false],
            ],

            // ===========================
            // SHOP
            // ===========================
            'Shop' => [
                ...$shared,
                ['name' => 'Floor Number', 'required' => false],
                ['name' => 'Ceiling Height', 'required' => false],
                ['name' => 'Street Width', 'required' => false],
                ['name' => 'Number of Bathrooms', 'required' => false],
                ['name' => 'Furnishing Status', 'required' => false],
                ['name' => 'Cooling Type', 'required' => false],
                ['name' => 'Elevator', 'required' => false],
                ['name' => 'Security', 'required' => false],
                ['name' => 'Parking', 'required' => false],
                ['name' => 'Fire System', 'required' => false],
                ['name' => 'CCTV', 'required' => false],
            ],
        ];
    }
}
