<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeOption;
use App\Models\City;
use App\Models\Developer;
use App\Models\Phase;
use App\Models\Project;
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

        $project = Project::firstOrCreate(
            ['id' => 1],
            ['name' => 'Default Project', 'developer_id' => $developer->id],
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

        $phase = Phase::firstOrCreate(
            ['id' => 1],
            ['name' => 'Default Phase', 'project_id' => $project->id, 'property_type_id' => $apartmentTypeId],
        );

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
                    'project_id' => $project->id,
                    'phase_id' => $phase->id,
                    'latitude' => 30.0626,
                    'longitude' => 31.2497,
                ],
                'scalar_values' => [
                    'Area' => '180',
                    'Built-up Area' => '165',
                    'Number of Bedrooms' => '3',
                    'Number of Bathrooms' => '2',
                    'Number of Living Rooms' => '1',
                    'Number of Kitchens' => '1',
                    'Floor Number' => '7',
                    'Total Price' => '3500000',
                    'Price per m²' => '19444',
                    'Down Payment' => '20',
                    'Monthly Installment' => '35000',
                    'Installment Period' => '60',
                    'Ceiling Height' => '3',
                ],
                'select_options' => [
                    'Finishing Type' => ['Super Lux'],
                    'View' => ['City View', 'Garden View'],
                    'Direction' => ['North'],
                    'Furnishing Status' => ['Furnished'],
                    'Payment Method' => ['Installment'],
                    'Ownership Type' => ['Freehold'],
                    'Property Condition' => ['New'],
                    'Flooring Type' => ['Porcelain'],
                    'Cooling Type' => ['Split AC'],
                ],
                'boolean_true' => [
                    'Balcony', 'Parking', 'Elevator', 'Security', 'Air Conditioning',
                    'Intercom', 'CCTV', 'Satellite/Cable TV', 'Electricity', 'Water Supply',
                    'Natural Gas', 'Internet',
                ],
            ],
            [
                'data' => [
                    'name' => ['en' => 'Modern Apartment in New Cairo', 'ar' => 'شقة حديثة في القاهرة الجديدة'],
                    'property_type_id' => $apartmentTypeId,
                    'city_id' => $cityId,
                    'status' => 'active',
                    'address' => '5th Settlement, New Cairo',
                    'project_id' => $project->id,
                    'phase_id' => $phase->id,
                    'latitude' => 30.0074,
                    'longitude' => 31.4913,
                ],
                'scalar_values' => [
                    'Area' => '220',
                    'Number of Bedrooms' => '4',
                    'Number of Bathrooms' => '3',
                    'Number of Living Rooms' => '2',
                    'Number of Kitchens' => '1',
                    'Floor Number' => '3',
                    'Total Price' => '5200000',
                    'Price per m²' => '23636',
                    'Down Payment' => '30',
                    'Monthly Installment' => '45000',
                    'Installment Period' => '84',
                ],
                'select_options' => [
                    'Finishing Type' => ['Extra Super Lux'],
                    'View' => ['Garden View', 'Pool View'],
                    'Direction' => ['Northeast'],
                    'Furnishing Status' => ['Semi-Furnished'],
                    'Payment Method' => ['Cash & Installment'],
                    'Ownership Type' => ['Freehold'],
                    'Property Condition' => ['Ready to Move'],
                    'Flooring Type' => ['Marble'],
                    'Cooling Type' => ['Central AC'],
                    'Heating Type' => ['Central Heating'],
                ],
                'boolean_true' => [
                    'Balcony', 'Parking', 'Elevator', 'Security', 'Swimming Pool',
                    'Gym', 'Air Conditioning', 'Intercom', 'Fire System', 'CCTV',
                    'Smart Home', "Maid's Room", 'Storage Room', 'Electricity',
                    'Water Supply', 'Natural Gas', 'Internet',
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
                    'project_id' => $project->id,
                    'latitude' => 30.1069,
                    'longitude' => 31.6388,
                ],
                'scalar_values' => [
                    'Area' => '450',
                    'Land Area' => '600',
                    'Built-up Area' => '380',
                    'Garden Area' => '120',
                    'Roof Area' => '80',
                    'Number of Bedrooms' => '5',
                    'Number of Bathrooms' => '4',
                    'Number of Living Rooms' => '2',
                    'Number of Kitchens' => '2',
                    'Number of Floors' => '3',
                    'Number of Parking Spaces' => '2',
                    'Street Width' => '16',
                    'Total Price' => '15000000',
                    'Price per m²' => '25000',
                    'Down Payment' => '25',
                    'Monthly Installment' => '120000',
                    'Installment Period' => '96',
                ],
                'select_options' => [
                    'Finishing Type' => ['Extra Super Lux'],
                    'View' => ['Garden View', 'Landscape View'],
                    'Direction' => ['North'],
                    'Furnishing Status' => ['Furnished'],
                    'Payment Method' => ['Cash & Installment'],
                    'Ownership Type' => ['Freehold'],
                    'Property Condition' => ['New'],
                    'Flooring Type' => ['Marble', 'Parquet'],
                    'Cooling Type' => ['Central AC'],
                    'Heating Type' => ['Central Heating'],
                ],
                'boolean_true' => [
                    'Balcony', 'Parking', 'Security', 'Swimming Pool', 'Gym',
                    'Garden', 'Air Conditioning', 'Central Gas', 'Internet',
                    'Fire System', 'CCTV', 'Smart Home', 'Solar Energy',
                    "Maid's Room", "Driver's Room", 'Storage Room', 'Laundry Room',
                    'Private Garden', 'Private Pool', 'Jacuzzi', 'Roof Access', 'Terrace',
                    'Electricity', 'Water Supply', 'Natural Gas', 'Doorman',
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
                    'project_id' => $project->id,
                    'latitude' => 30.0444,
                    'longitude' => 31.2357,
                ],
                'scalar_values' => [
                    'Area' => '55',
                    'Number of Bathrooms' => '1',
                    'Floor Number' => '5',
                    'Total Price' => '850000',
                    'Price per m²' => '15454',
                ],
                'select_options' => [
                    'Finishing Type' => ['Fully Finished'],
                    'View' => ['City View'],
                    'Direction' => ['West'],
                    'Furnishing Status' => ['Furnished'],
                    'Payment Method' => ['Cash'],
                    'Property Condition' => ['Renovated'],
                    'Flooring Type' => ['Ceramic'],
                    'Cooling Type' => ['Split AC'],
                ],
                'boolean_true' => [
                    'Elevator', 'Security', 'Air Conditioning', 'Internet',
                    'Electricity', 'Water Supply',
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
                    'project_id' => $project->id,
                    'latitude' => 30.0616,
                    'longitude' => 31.2213,
                ],
                'scalar_values' => [
                    'Area' => '350',
                    'Roof Area' => '150',
                    'Number of Bedrooms' => '4',
                    'Number of Bathrooms' => '3',
                    'Number of Living Rooms' => '2',
                    'Number of Kitchens' => '1',
                    'Floor Number' => '12',
                    'Total Price' => '25000000',
                    'Price per m²' => '50000',
                ],
                'select_options' => [
                    'Finishing Type' => ['Extra Super Lux'],
                    'View' => ['Nile View', 'City View'],
                    'Direction' => ['West'],
                    'Furnishing Status' => ['Furnished'],
                    'Payment Method' => ['Cash'],
                    'Ownership Type' => ['Freehold'],
                    'Property Condition' => ['New'],
                    'Flooring Type' => ['Marble', 'Parquet'],
                    'Cooling Type' => ['Central AC'],
                    'Heating Type' => ['Underfloor Heating'],
                ],
                'boolean_true' => [
                    'Balcony', 'Parking', 'Elevator', 'Security', 'Swimming Pool',
                    'Gym', 'Air Conditioning', 'Smart Home', 'Fire System', 'CCTV',
                    "Maid's Room", 'Laundry Room', 'Private Pool', 'Jacuzzi',
                    'Roof Access', 'Terrace', 'Electricity', 'Water Supply',
                    'Natural Gas', 'Internet',
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
                    'project_id' => $project->id,
                    'latitude' => 30.0715,
                    'longitude' => 31.0175,
                ],
                'scalar_values' => [
                    'Area' => '120',
                    'Number of Rooms' => '4',
                    'Number of Bathrooms' => '2',
                    'Floor Number' => '3',
                    'Total Price' => '4000000',
                    'Price per m²' => '33333',
                    'Ceiling Height' => '3.5',
                    'Building Age' => '2',
                ],
                'select_options' => [
                    'Finishing Type' => ['Fully Finished'],
                    'View' => ['Landscape View'],
                    'Direction' => ['South'],
                    'Furnishing Status' => ['Semi-Furnished'],
                    'Payment Method' => ['Installment'],
                    'Property Condition' => ['Ready to Move'],
                    'Flooring Type' => ['Porcelain'],
                    'Cooling Type' => ['Central AC'],
                ],
                'boolean_true' => [
                    'Parking', 'Elevator', 'Security', 'Air Conditioning',
                    'Fire System', 'CCTV', 'Intercom', 'Electricity',
                    'Water Supply', 'Internet',
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
                    'project_id' => $project->id,
                    'latitude' => 30.0867,
                    'longitude' => 31.3225,
                ],
                'scalar_values' => [
                    'Area' => '80',
                    'Number of Bathrooms' => '1',
                    'Floor Number' => '0',
                    'Total Price' => '6000000',
                    'Price per m²' => '75000',
                    'Ceiling Height' => '4',
                    'Street Width' => '24',
                ],
                'select_options' => [
                    'Finishing Type' => ['Core & Shell'],
                    'View' => ['Main Road View'],
                    'Direction' => ['East'],
                    'Furnishing Status' => ['Unfurnished'],
                    'Payment Method' => ['Cash & Installment'],
                    'Property Condition' => ['New'],
                    'Cooling Type' => ['No AC'],
                ],
                'boolean_true' => [
                    'Parking', 'Security', 'Fire System', 'CCTV',
                    'Electricity', 'Water Supply',
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
                    'project_id' => $project->id,
                    'latitude' => 29.9712,
                    'longitude' => 31.0156,
                ],
                'scalar_values' => [
                    'Area' => '250',
                    'Land Area' => '300',
                    'Built-up Area' => '220',
                    'Garden Area' => '40',
                    'Number of Bedrooms' => '4',
                    'Number of Bathrooms' => '3',
                    'Number of Living Rooms' => '1',
                    'Number of Kitchens' => '1',
                    'Number of Floors' => '2',
                    'Number of Parking Spaces' => '1',
                    'Total Price' => '8000000',
                    'Price per m²' => '26666',
                    'Down Payment' => '15',
                    'Monthly Installment' => '70000',
                    'Installment Period' => '96',
                ],
                'select_options' => [
                    'Finishing Type' => ['Super Lux'],
                    'View' => ['Landscape View', 'Garden View'],
                    'Direction' => ['Southeast'],
                    'Furnishing Status' => ['Semi-Furnished'],
                    'Payment Method' => ['Installment'],
                    'Ownership Type' => ['Freehold'],
                    'Property Condition' => ['Under Construction'],
                    'Flooring Type' => ['Porcelain', 'HDF'],
                    'Cooling Type' => ['Split AC'],
                ],
                'boolean_true' => [
                    'Balcony', 'Parking', 'Security', 'Garden', 'Air Conditioning',
                    'Central Gas', 'Internet', 'Private Garden', 'Terrace',
                    'Electricity', 'Water Supply', 'Natural Gas', 'Storage Room',
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
        $this->attributes = Attribute::all()->keyBy(fn ($a) => $a->getTranslation('name', 'en'));

        $this->optionsByAttribute = AttributeOption::with('attribute')->get()
            ->groupBy(fn ($o) => $o->attribute->getTranslation('name', 'en'))
            ->map(fn ($options) => $options->keyBy(fn ($o) => $o->getTranslation('value', 'en')));
    }

    /**
     * @param  array<string, string>  $values
     */
    private function attachScalarAttributes(Property $property, array $values): void
    {
        $syncData = [];

        foreach ($values as $attributeName => $value) {
            $attribute = $this->attributes->get($attributeName);

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
     * @param  array<int, string>  $attributeNames
     */
    private function attachBooleanAttributes(Property $property, array $attributeNames): void
    {
        $syncData = [];

        foreach ($attributeNames as $attributeName) {
            $attribute = $this->attributes->get($attributeName);

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

        foreach ($selections as $attributeName => $optionNames) {
            $attribute = $this->attributes->get($attributeName);
            $options = $this->optionsByAttribute->get($attributeName);

            if (! $attribute || ! $options) {
                continue;
            }

            // Ensure attribute is linked in attribute_property pivot
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
