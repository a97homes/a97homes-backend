<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\City;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;

class PropertySeeder extends Seeder
{
    /** @var Collection<string, Attribute> */
    private Collection $attributes;

    /** @var Collection<string, Collection<string, AttributeOption>> */
    private Collection $optionsByAttribute;

    public function run(): void
    {
        $this->loadAttributesAndOptions();

        $developer = Developer::firstOrCreate(
            ['id' => 1],
            ['name' => 'Default Developer', 'about' => 'Developer for seeding purposes'],
        );

        $compound = Compound::firstOrCreate(
            ['id' => 1],
            ['name' => 'Default Compound', 'developer_id' => $developer->id],
        );

        $propertyTypes = PropertyType::all()->keyBy(fn ($pt) => $pt->getTranslation('name', 'en'));

        $cityId = City::where('name->en', 'Nasr City')->value('id')
            ?? City::firstOrCreate(['name->en' => 'Nasr City'], ['name' => ['en' => 'Nasr City', 'ar' => 'مدينة نصر']])->id;

        $apartmentTypeId = $propertyTypes->get('Apartment')?->id;
        $villaTypeId = $propertyTypes->get('Villa')?->id;
        $studioTypeId = $propertyTypes->get('Studio')?->id;
        $penthouseTypeId = $propertyTypes->get('Penthouse')?->id;
        $officeTypeId = $propertyTypes->get('Office')?->id;
        $shopTypeId = $propertyTypes->get('Shop')?->id;
        $townhouseTypeId = $propertyTypes->get('Townhouse')?->id;

        $properties = [
            // ===========================
            // APARTMENTS
            // ===========================
            [
                'data' => [
                    'name' => ['en' => 'Luxury Apartment in Nasr City', 'ar' => 'شقة فاخرة في مدينة نصر'],
                    'property_type_id' => $apartmentTypeId,
                    'city_id' => $cityId,
                    'status' => 'active',
                    'address' => '15 Abbas El-Akkad Street, Nasr City',
                    'compound_id' => $compound->id,
                    'latitude' => 30.0626,
                    'longitude' => 31.2497,
                ],
                'scalar_values' => [
                    'area' => '180',
                    'built-up-area' => '165',
                    'number-of-bedrooms' => '3',
                    'number-of-bathrooms' => '2',
                    'number-of-living-rooms' => '1',
                    'number-of-kitchens' => '1',
                    'floor-number' => '7',
                    'total-price' => '3500000',
                    'price-per-m2' => '19444',
                    'down-payment' => '20',
                    'monthly-installment' => '35000',
                    'installment-period' => '60',
                    'ceiling-height' => '3',
                ],
                'select_options' => [
                    'finishing-type' => ['Super Lux'],
                    'view' => ['City View', 'Garden View'],
                    'direction' => ['North'],
                    'furnishing-status' => ['Furnished'],
                    'payment-method' => ['Installment'],
                    'ownership-type' => ['Freehold'],
                    'property-condition' => ['New'],
                    'flooring-type' => ['Porcelain'],
                    'cooling-type' => ['Split AC'],
                ],
                'boolean_true' => [
                    'balcony', 'parking', 'elevator', 'security', 'air-conditioning',
                    'intercom', 'cctv', 'satellite-cable-tv', 'electricity', 'water-supply',
                    'natural-gas', 'internet',
                ],
            ],
            [
                'data' => [
                    'name' => ['en' => 'Modern Apartment in New Cairo', 'ar' => 'شقة حديثة في القاهرة الجديدة'],
                    'property_type_id' => $apartmentTypeId,
                    'city_id' => $cityId,
                    'status' => 'active',
                    'address' => '5th Settlement, New Cairo',
                    'compound_id' => $compound->id,
                    'latitude' => 30.0074,
                    'longitude' => 31.4913,
                ],
                'scalar_values' => [
                    'area' => '220',
                    'number-of-bedrooms' => '4',
                    'number-of-bathrooms' => '3',
                    'number-of-living-rooms' => '2',
                    'number-of-kitchens' => '1',
                    'floor-number' => '3',
                    'total-price' => '5200000',
                    'price-per-m2' => '23636',
                    'down-payment' => '30',
                    'monthly-installment' => '45000',
                    'installment-period' => '84',
                ],
                'select_options' => [
                    'finishing-type' => ['Extra Super Lux'],
                    'view' => ['Garden View', 'Pool View'],
                    'direction' => ['Northeast'],
                    'furnishing-status' => ['Semi-Furnished'],
                    'payment-method' => ['Cash & Installment'],
                    'ownership-type' => ['Freehold'],
                    'property-condition' => ['Ready to Move'],
                    'flooring-type' => ['Marble'],
                    'cooling-type' => ['Central AC'],
                    'heating-type' => ['Central Heating'],
                ],
                'boolean_true' => [
                    'balcony', 'parking', 'elevator', 'security', 'swimming-pool',
                    'gym', 'air-conditioning', 'intercom', 'fire-system', 'cctv',
                    'smart-home', 'maids-room', 'storage-room', 'electricity',
                    'water-supply', 'natural-gas', 'internet',
                ],
            ],

            // ===========================
            // VILLA
            // ===========================
            [
                'data' => [
                    'name' => ['en' => 'Standalone Villa in Madinaty', 'ar' => 'فيلا مستقلة في مدينتي'],
                    'property_type_id' => $villaTypeId,
                    'city_id' => $cityId,
                    'status' => 'active',
                    'address' => 'Madinaty, New Cairo',
                    'compound_id' => $compound->id,
                    'latitude' => 30.1069,
                    'longitude' => 31.6388,
                ],
                'scalar_values' => [
                    'area' => '450',
                    'land-area' => '600',
                    'built-up-area' => '380',
                    'garden-area' => '120',
                    'roof-area' => '80',
                    'number-of-bedrooms' => '5',
                    'number-of-bathrooms' => '4',
                    'number-of-living-rooms' => '2',
                    'number-of-kitchens' => '2',
                    'number-of-floors' => '3',
                    'number-of-parking-spaces' => '2',
                    'street-width' => '16',
                    'total-price' => '15000000',
                    'price-per-m2' => '25000',
                    'down-payment' => '25',
                    'monthly-installment' => '120000',
                    'installment-period' => '96',
                ],
                'select_options' => [
                    'finishing-type' => ['Extra Super Lux'],
                    'view' => ['Garden View', 'Landscape View'],
                    'direction' => ['North'],
                    'furnishing-status' => ['Furnished'],
                    'payment-method' => ['Cash & Installment'],
                    'ownership-type' => ['Freehold'],
                    'property-condition' => ['New'],
                    'flooring-type' => ['Marble', 'Parquet'],
                    'cooling-type' => ['Central AC'],
                    'heating-type' => ['Central Heating'],
                ],
                'boolean_true' => [
                    'balcony', 'parking', 'security', 'swimming-pool', 'gym',
                    'garden', 'air-conditioning', 'central-gas', 'internet',
                    'fire-system', 'cctv', 'smart-home', 'solar-energy',
                    'maids-room', 'drivers-room', 'storage-room', 'laundry-room',
                    'private-garden', 'private-pool', 'jacuzzi', 'roof-access', 'terrace',
                    'electricity', 'water-supply', 'natural-gas', 'doorman',
                ],
            ],

            // ===========================
            // STUDIO
            // ===========================
            [
                'data' => [
                    'name' => ['en' => 'Cozy Studio in Downtown', 'ar' => 'استوديو مريح في وسط البلد'],
                    'property_type_id' => $studioTypeId,
                    'city_id' => $cityId,
                    'status' => 'active',
                    'address' => 'Talaat Harb Street, Downtown Cairo',
                    'compound_id' => $compound->id,
                    'latitude' => 30.0444,
                    'longitude' => 31.2357,
                ],
                'scalar_values' => [
                    'area' => '55',
                    'number-of-bathrooms' => '1',
                    'floor-number' => '5',
                    'total-price' => '850000',
                    'price-per-m2' => '15454',
                ],
                'select_options' => [
                    'finishing-type' => ['Fully Finished'],
                    'view' => ['City View'],
                    'direction' => ['West'],
                    'furnishing-status' => ['Furnished'],
                    'payment-method' => ['Cash'],
                    'property-condition' => ['Renovated'],
                    'flooring-type' => ['Ceramic'],
                    'cooling-type' => ['Split AC'],
                ],
                'boolean_true' => [
                    'elevator', 'security', 'air-conditioning', 'internet',
                    'electricity', 'water-supply',
                ],
            ],

            // ===========================
            // PENTHOUSE
            // ===========================
            [
                'data' => [
                    'name' => ['en' => 'Luxury Penthouse in Zamalek', 'ar' => 'بنتهاوس فاخر في الزمالك'],
                    'property_type_id' => $penthouseTypeId,
                    'city_id' => $cityId,
                    'status' => 'active',
                    'address' => '26th of July Street, Zamalek',
                    'compound_id' => $compound->id,
                    'latitude' => 30.0616,
                    'longitude' => 31.2213,
                ],
                'scalar_values' => [
                    'area' => '350',
                    'roof-area' => '150',
                    'number-of-bedrooms' => '4',
                    'number-of-bathrooms' => '3',
                    'number-of-living-rooms' => '2',
                    'number-of-kitchens' => '1',
                    'floor-number' => '12',
                    'total-price' => '25000000',
                    'price-per-m2' => '50000',
                ],
                'select_options' => [
                    'finishing-type' => ['Extra Super Lux'],
                    'view' => ['Nile View', 'City View'],
                    'direction' => ['West'],
                    'furnishing-status' => ['Furnished'],
                    'payment-method' => ['Cash'],
                    'ownership-type' => ['Freehold'],
                    'property-condition' => ['New'],
                    'flooring-type' => ['Marble', 'Parquet'],
                    'cooling-type' => ['Central AC'],
                    'heating-type' => ['Underfloor Heating'],
                ],
                'boolean_true' => [
                    'balcony', 'parking', 'elevator', 'security', 'swimming-pool',
                    'gym', 'air-conditioning', 'smart-home', 'fire-system', 'cctv',
                    'maids-room', 'laundry-room', 'private-pool', 'jacuzzi',
                    'roof-access', 'terrace', 'electricity', 'water-supply',
                    'natural-gas', 'internet',
                ],
            ],

            // ===========================
            // OFFICE
            // ===========================
            [
                'data' => [
                    'name' => ['en' => 'Office Space in Smart Village', 'ar' => 'مكتب في القرية الذكية'],
                    'property_type_id' => $officeTypeId,
                    'city_id' => $cityId,
                    'status' => 'active',
                    'address' => 'Smart Village, 6th of October',
                    'compound_id' => $compound->id,
                    'latitude' => 30.0715,
                    'longitude' => 31.0175,
                ],
                'scalar_values' => [
                    'area' => '120',
                    'number-of-rooms' => '4',
                    'number-of-bathrooms' => '2',
                    'floor-number' => '3',
                    'total-price' => '4000000',
                    'price-per-m2' => '33333',
                    'ceiling-height' => '3.5',
                    'building-age' => '2',
                ],
                'select_options' => [
                    'finishing-type' => ['Fully Finished'],
                    'view' => ['Landscape View'],
                    'direction' => ['South'],
                    'furnishing-status' => ['Semi-Furnished'],
                    'payment-method' => ['Installment'],
                    'property-condition' => ['Ready to Move'],
                    'flooring-type' => ['Porcelain'],
                    'cooling-type' => ['Central AC'],
                ],
                'boolean_true' => [
                    'parking', 'elevator', 'security', 'air-conditioning',
                    'fire-system', 'cctv', 'intercom', 'electricity',
                    'water-supply', 'internet',
                ],
            ],

            // ===========================
            // SHOP
            // ===========================
            [
                'data' => [
                    'name' => ['en' => 'Commercial Shop in Heliopolis', 'ar' => 'محل تجاري في مصر الجديدة'],
                    'property_type_id' => $shopTypeId,
                    'city_id' => $cityId,
                    'status' => 'active',
                    'address' => 'Al-Merghany Street, Heliopolis',
                    'compound_id' => $compound->id,
                    'latitude' => 30.0867,
                    'longitude' => 31.3225,
                ],
                'scalar_values' => [
                    'area' => '80',
                    'number-of-bathrooms' => '1',
                    'floor-number' => '0',
                    'total-price' => '6000000',
                    'price-per-m2' => '75000',
                    'ceiling-height' => '4',
                    'street-width' => '24',
                ],
                'select_options' => [
                    'finishing-type' => ['Core & Shell'],
                    'view' => ['Main Road View'],
                    'direction' => ['East'],
                    'furnishing-status' => ['Unfurnished'],
                    'payment-method' => ['Cash & Installment'],
                    'property-condition' => ['New'],
                    'cooling-type' => ['No AC'],
                ],
                'boolean_true' => [
                    'parking', 'security', 'fire-system', 'cctv',
                    'electricity', 'water-supply',
                ],
            ],

            // ===========================
            // TOWNHOUSE
            // ===========================
            [
                'data' => [
                    'name' => ['en' => 'Townhouse in Palm Hills', 'ar' => 'تاون هاوس في بالم هيلز'],
                    'property_type_id' => $townhouseTypeId,
                    'city_id' => $cityId,
                    'status' => 'active',
                    'address' => 'Palm Hills, 6th of October',
                    'compound_id' => $compound->id,
                    'latitude' => 29.9712,
                    'longitude' => 31.0156,
                ],
                'scalar_values' => [
                    'area' => '250',
                    'land-area' => '300',
                    'built-up-area' => '220',
                    'garden-area' => '40',
                    'number-of-bedrooms' => '4',
                    'number-of-bathrooms' => '3',
                    'number-of-living-rooms' => '1',
                    'number-of-kitchens' => '1',
                    'number-of-floors' => '2',
                    'number-of-parking-spaces' => '1',
                    'total-price' => '8000000',
                    'price-per-m2' => '26666',
                    'down-payment' => '15',
                    'monthly-installment' => '70000',
                    'installment-period' => '96',
                ],
                'select_options' => [
                    'finishing-type' => ['Super Lux'],
                    'view' => ['Landscape View', 'Garden View'],
                    'direction' => ['Southeast'],
                    'furnishing-status' => ['Semi-Furnished'],
                    'payment-method' => ['Installment'],
                    'ownership-type' => ['Freehold'],
                    'property-condition' => ['Under Construction'],
                    'flooring-type' => ['Porcelain', 'HDF'],
                    'cooling-type' => ['Split AC'],
                ],
                'boolean_true' => [
                    'balcony', 'parking', 'security', 'garden', 'air-conditioning',
                    'central-gas', 'internet', 'private-garden', 'terrace',
                    'electricity', 'water-supply', 'natural-gas', 'storage-room',
                ],
            ],
        ];

        foreach ($properties as $propertyData) {
            $property = Property::updateOrCreate(
                ['name->en' => $propertyData['data']['name']['en']],
                $propertyData['data'],
            );

            $this->attachScalarAttributes($property, $propertyData['scalar_values'] ?? []);
            $this->attachBooleanAttributes($property, $propertyData['boolean_true'] ?? []);
            $this->attachSelectOptions($property, $propertyData['select_options'] ?? []);
        }
    }

    private function loadAttributesAndOptions(): void
    {
        $this->attributes = Attribute::all()->keyBy('slug');

        $this->optionsByAttribute = AttributeOption::with('attribute')->get()
            ->groupBy(fn ($o) => $o->attribute->slug)
            ->map(fn ($options) => $options->keyBy(fn ($o) => $o->getTranslation('value', 'en')));
    }

    /**
     * @param  array<string, string>  $values
     */
    private function attachScalarAttributes(Property $property, array $values): void
    {
        $syncData = [];

        foreach ($values as $slug => $value) {
            $attribute = $this->attributes->get($slug);

            if (! $attribute) {
                continue;
            }

            $syncData[$attribute->id] = ['value' => $value];
        }

        if ($syncData) {
            $property->attributes()->syncWithoutDetaching($syncData);
        }
    }

    /**
     * @param  array<int, string>  $slugs
     */
    private function attachBooleanAttributes(Property $property, array $slugs): void
    {
        $syncData = [];

        foreach ($slugs as $slug) {
            $attribute = $this->attributes->get($slug);

            if (! $attribute) {
                continue;
            }

            $syncData[$attribute->id] = ['value' => '1'];
        }

        if ($syncData) {
            $property->attributes()->syncWithoutDetaching($syncData);
        }
    }

    /**
     * @param  array<string, array<int, string>>  $selections
     */
    private function attachSelectOptions(Property $property, array $selections): void
    {
        $pivotInserts = [];

        foreach ($selections as $slug => $optionNames) {
            $attribute = $this->attributes->get($slug);
            $options = $this->optionsByAttribute->get($slug);

            if (! $attribute || ! $options) {
                continue;
            }

            $property->attributes()->syncWithoutDetaching([
                $attribute->id => ['value' => null],
            ]);

            foreach ($optionNames as $optionName) {
                $option = $options->get($optionName);

                if (! $option) {
                    continue;
                }

                $pivotInserts[$option->id] = ['attribute_id' => $attribute->id];
            }
        }

        if ($pivotInserts) {
            $property->selectedOptions()->syncWithoutDetaching($pivotInserts);
        }
    }
}
