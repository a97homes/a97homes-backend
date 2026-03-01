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
        $attributes = Attribute::all()->keyBy('slug');

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
                $attribute = $attributes->get($assignment['slug']);

                if (! $attribute) {
                    continue;
                }

                $syncData[$attribute->id] = ['is_required' => $assignment['required']];
            }

            $propertyType->attributes()->syncWithoutDetaching($syncData);
        }
    }

    /**
     * @return array<string, array<int, array{slug: string, required: bool}>>
     */
    private function getMapping(): array
    {
        $shared = [
            ['slug' => 'area', 'required' => true],
            ['slug' => 'total-price', 'required' => true],
            ['slug' => 'finishing-type', 'required' => true],
            ['slug' => 'payment-method', 'required' => true],
            ['slug' => 'ownership-type', 'required' => false],
            ['slug' => 'property-condition', 'required' => false],
            ['slug' => 'view', 'required' => false],
            ['slug' => 'direction', 'required' => false],
            ['slug' => 'price-per-m2', 'required' => false],
            ['slug' => 'down-payment', 'required' => false],
            ['slug' => 'monthly-installment', 'required' => false],
            ['slug' => 'installment-period', 'required' => false],
            ['slug' => 'delivery-date', 'required' => false],
            ['slug' => 'electricity', 'required' => false],
            ['slug' => 'water-supply', 'required' => false],
            ['slug' => 'natural-gas', 'required' => false],
            ['slug' => 'internet', 'required' => false],
        ];

        $residentialCommon = [
            ...$shared,
            ['slug' => 'number-of-bedrooms', 'required' => true],
            ['slug' => 'number-of-bathrooms', 'required' => true],
            ['slug' => 'number-of-rooms', 'required' => false],
            ['slug' => 'number-of-living-rooms', 'required' => false],
            ['slug' => 'number-of-kitchens', 'required' => false],
            ['slug' => 'furnishing-status', 'required' => true],
            ['slug' => 'flooring-type', 'required' => false],
            ['slug' => 'heating-type', 'required' => false],
            ['slug' => 'cooling-type', 'required' => false],
            ['slug' => 'balcony', 'required' => false],
            ['slug' => 'parking', 'required' => false],
            ['slug' => 'elevator', 'required' => false],
            ['slug' => 'security', 'required' => false],
            ['slug' => 'air-conditioning', 'required' => false],
            ['slug' => 'central-gas', 'required' => false],
            ['slug' => 'intercom', 'required' => false],
            ['slug' => 'satellite-cable-tv', 'required' => false],
            ['slug' => 'fire-system', 'required' => false],
            ['slug' => 'cctv', 'required' => false],
            ['slug' => 'smart-home', 'required' => false],
            ['slug' => 'maids-room', 'required' => false],
            ['slug' => 'drivers-room', 'required' => false],
            ['slug' => 'storage-room', 'required' => false],
            ['slug' => 'laundry-room', 'required' => false],
        ];

        return [
            // ===========================
            // APARTMENT
            // ===========================
            'Apartment' => [
                ...$residentialCommon,
                ['slug' => 'built-up-area', 'required' => false],
                ['slug' => 'floor-number', 'required' => true],
                ['slug' => 'number-of-floors', 'required' => false],
                ['slug' => 'building-age', 'required' => false],
                ['slug' => 'ceiling-height', 'required' => false],
            ],

            // ===========================
            // VILLA
            // ===========================
            'Villa' => [
                ...$residentialCommon,
                ['slug' => 'land-area', 'required' => true],
                ['slug' => 'built-up-area', 'required' => false],
                ['slug' => 'garden-area', 'required' => false],
                ['slug' => 'roof-area', 'required' => false],
                ['slug' => 'number-of-floors', 'required' => true],
                ['slug' => 'number-of-parking-spaces', 'required' => false],
                ['slug' => 'street-width', 'required' => false],
                ['slug' => 'private-garden', 'required' => false],
                ['slug' => 'private-pool', 'required' => false],
                ['slug' => 'jacuzzi', 'required' => false],
                ['slug' => 'roof-access', 'required' => false],
                ['slug' => 'terrace', 'required' => false],
                ['slug' => 'garden', 'required' => false],
                ['slug' => 'swimming-pool', 'required' => false],
                ['slug' => 'gym', 'required' => false],
                ['slug' => 'solar-energy', 'required' => false],
                ['slug' => 'doorman', 'required' => false],
            ],

            // ===========================
            // STUDIO
            // ===========================
            'Studio' => [
                ...$shared,
                ['slug' => 'number-of-bathrooms', 'required' => true],
                ['slug' => 'furnishing-status', 'required' => true],
                ['slug' => 'floor-number', 'required' => true],
                ['slug' => 'flooring-type', 'required' => false],
                ['slug' => 'cooling-type', 'required' => false],
                ['slug' => 'balcony', 'required' => false],
                ['slug' => 'elevator', 'required' => false],
                ['slug' => 'security', 'required' => false],
                ['slug' => 'air-conditioning', 'required' => false],
                ['slug' => 'building-age', 'required' => false],
            ],

            // ===========================
            // PENTHOUSE
            // ===========================
            'Penthouse' => [
                ...$residentialCommon,
                ['slug' => 'roof-area', 'required' => false],
                ['slug' => 'floor-number', 'required' => true],
                ['slug' => 'number-of-floors', 'required' => false],
                ['slug' => 'private-pool', 'required' => false],
                ['slug' => 'jacuzzi', 'required' => false],
                ['slug' => 'roof-access', 'required' => false],
                ['slug' => 'terrace', 'required' => false],
                ['slug' => 'swimming-pool', 'required' => false],
                ['slug' => 'gym', 'required' => false],
            ],

            // ===========================
            // TOWNHOUSE
            // ===========================
            'Townhouse' => [
                ...$residentialCommon,
                ['slug' => 'land-area', 'required' => false],
                ['slug' => 'built-up-area', 'required' => false],
                ['slug' => 'garden-area', 'required' => false],
                ['slug' => 'number-of-floors', 'required' => true],
                ['slug' => 'number-of-parking-spaces', 'required' => false],
                ['slug' => 'private-garden', 'required' => false],
                ['slug' => 'terrace', 'required' => false],
                ['slug' => 'garden', 'required' => false],
            ],

            // ===========================
            // COMPOUND
            // ===========================
            'Compound' => [
                ['slug' => 'area', 'required' => true],
                ['slug' => 'total-price', 'required' => false],
                ['slug' => 'number-of-units', 'required' => false],
                ['slug' => 'payment-method', 'required' => false],
                ['slug' => 'delivery-date', 'required' => false],
                ['slug' => 'security', 'required' => false],
                ['slug' => 'swimming-pool', 'required' => false],
                ['slug' => 'gym', 'required' => false],
                ['slug' => 'garden', 'required' => false],
                ['slug' => 'clubhouse', 'required' => false],
                ['slug' => 'kids-area', 'required' => false],
                ['slug' => 'commercial-area', 'required' => false],
                ['slug' => 'mosque', 'required' => false],
                ['slug' => 'jogging-track', 'required' => false],
                ['slug' => 'sports-courts', 'required' => false],
                ['slug' => 'spa', 'required' => false],
                ['slug' => 'business-center', 'required' => false],
                ['slug' => 'medical-center', 'required' => false],
                ['slug' => 'school-nearby', 'required' => false],
                ['slug' => 'hospital-nearby', 'required' => false],
                ['slug' => 'mall-nearby', 'required' => false],
                ['slug' => 'metro-nearby', 'required' => false],
                ['slug' => 'highway-access', 'required' => false],
                ['slug' => 'public-transport-nearby', 'required' => false],
                ['slug' => 'cctv', 'required' => false],
                ['slug' => 'fire-system', 'required' => false],
            ],

            // ===========================
            // OFFICE
            // ===========================
            'Office' => [
                ...$shared,
                ['slug' => 'floor-number', 'required' => true],
                ['slug' => 'number-of-rooms', 'required' => false],
                ['slug' => 'number-of-bathrooms', 'required' => false],
                ['slug' => 'furnishing-status', 'required' => false],
                ['slug' => 'cooling-type', 'required' => false],
                ['slug' => 'flooring-type', 'required' => false],
                ['slug' => 'elevator', 'required' => false],
                ['slug' => 'security', 'required' => false],
                ['slug' => 'parking', 'required' => false],
                ['slug' => 'air-conditioning', 'required' => false],
                ['slug' => 'fire-system', 'required' => false],
                ['slug' => 'cctv', 'required' => false],
                ['slug' => 'intercom', 'required' => false],
                ['slug' => 'building-age', 'required' => false],
                ['slug' => 'ceiling-height', 'required' => false],
            ],

            // ===========================
            // SHOP
            // ===========================
            'Shop' => [
                ...$shared,
                ['slug' => 'floor-number', 'required' => false],
                ['slug' => 'ceiling-height', 'required' => false],
                ['slug' => 'street-width', 'required' => false],
                ['slug' => 'number-of-bathrooms', 'required' => false],
                ['slug' => 'furnishing-status', 'required' => false],
                ['slug' => 'cooling-type', 'required' => false],
                ['slug' => 'elevator', 'required' => false],
                ['slug' => 'security', 'required' => false],
                ['slug' => 'parking', 'required' => false],
                ['slug' => 'fire-system', 'required' => false],
                ['slug' => 'cctv', 'required' => false],
            ],
        ];
    }
}
