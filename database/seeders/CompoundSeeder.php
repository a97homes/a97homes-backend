<?php

namespace Database\Seeders;

use App\Enums\CompletionStatusEnum;
use App\Models\City;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CompoundSeeder extends Seeder
{
    public function run(): void
    {
        // Reset PostgreSQL sequence to avoid conflicts with manually-inserted IDs
        $maxId = DB::table('compounds')->max('id') ?? 0;
        if ($maxId > 0) {
            DB::statement("SELECT setval('compounds_id_seq', ?, true)", [$maxId]);
        }

        $developers = Developer::all()->keyBy(fn (Developer $developer) => $developer->getTranslation('name', 'ar'));
        $propertyTypes = PropertyType::all()->keyBy(fn ($pt) => $pt->getTranslation('name', 'en'));

        $cities = [
            'New Cairo' => City::where('name->en', 'New Cairo')->value('id'),
            '6th of October' => City::where('name->en', '6th of October')->value('id'),
            'Sheikh Zayed' => City::where('name->en', 'Sheikh Zayed')->value('id'),
            'Nasr City' => City::where('name->en', 'Nasr City')->value('id'),
            'El Alamein' => City::where('name->en', 'El Alamein')->value('id'),
            'El Shorouk' => City::where('name->en', 'El Shorouk')->value('id'),
            'Hurghada' => City::where('name->en', 'Hurghada')->value('id'),
            'Maadi' => City::where('name->en', 'Maadi')->value('id'),
        ];

        $compounds = [
            [
                'name' => 'مراسي',
                'developer' => 'إعمار مصر',
                'city' => 'El Alamein',
                'status' => CompletionStatusEnum::Completed,
                'delivery_date' => '2024-06-01',
                'description' => [
                    'en' => 'Marassi is a premium coastal resort by Emaar Misr on the North Coast, offering luxurious living with stunning Mediterranean views.',
                    'ar' => 'مراسي هو منتجع ساحلي فاخر من إعمار مصر على الساحل الشمالي، يوفر حياة فاخرة مع إطلالات خلابة على البحر المتوسط.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 3500000, 'resale_price' => 4200000],
                    ['type' => 'Apartment', 'price' => 4800000, 'resale_price' => 5700000],
                    ['type' => 'Villa', 'price' => 15000000, 'resale_price' => 18000000],
                    ['type' => 'Townhouse', 'price' => 8500000, 'resale_price' => 10200000],
                    ['type' => 'Penthouse', 'price' => 12000000, 'resale_price' => 14500000],
                ],
            ],
            [
                'name' => 'أبتاون كايرو',
                'developer' => 'إعمار مصر',
                'city' => 'New Cairo',
                'status' => CompletionStatusEnum::UnderConstruction,
                'delivery_date' => '2027-12-01',
                'description' => [
                    'en' => 'Uptown Cairo is a mixed-use community in Mokattam Hills, offering residential, commercial and leisure facilities with panoramic views of Cairo.',
                    'ar' => 'أبتاون كايرو مجتمع متعدد الاستخدامات في تلال المقطم، يوفر مرافق سكنية وتجارية وترفيهية مع إطلالات بانورامية على القاهرة.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 4200000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 5600000, 'resale_price' => null],
                    ['type' => 'Villa', 'price' => 22000000, 'resale_price' => null],
                    ['type' => 'Townhouse', 'price' => 9800000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 3800000, 'resale_price' => null],
                    ['type' => 'Penthouse', 'price' => 18000000, 'resale_price' => null],
                ],
            ],
            [
                'name' => 'أليجريا',
                'developer' => 'سوديك',
                'city' => 'Sheikh Zayed',
                'status' => CompletionStatusEnum::Completed,
                'delivery_date' => '2023-03-01',
                'description' => [
                    'en' => 'Allegria is a gated residential compound by SODIC in Sheikh Zayed, featuring lush green spaces and modern architecture.',
                    'ar' => 'أليجريا هو كمبوند سكني مسور من سوديك في الشيخ زايد، يتميز بمساحات خضراء واسعة وعمارة حديثة.',
                ],
                'units' => [
                    ['type' => 'Villa', 'price' => 18000000, 'resale_price' => 22000000],
                    ['type' => 'Villa', 'price' => 25000000, 'resale_price' => 30000000],
                    ['type' => 'Townhouse', 'price' => 12000000, 'resale_price' => 14500000],
                    ['type' => 'Townhouse', 'price' => 10500000, 'resale_price' => 12800000],
                ],
            ],
            [
                'name' => 'فيليت',
                'developer' => 'سوديك',
                'city' => 'New Cairo',
                'status' => CompletionStatusEnum::UnderConstruction,
                'delivery_date' => '2028-06-01',
                'description' => [
                    'en' => 'Villette is a modern residential compound by SODIC in New Cairo, designed with a focus on community living and open spaces.',
                    'ar' => 'فيليت هو كمبوند سكني حديث من سوديك في القاهرة الجديدة، مصمم مع التركيز على الحياة المجتمعية والمساحات المفتوحة.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 3200000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 4500000, 'resale_price' => null],
                    ['type' => 'Villa', 'price' => 16000000, 'resale_price' => null],
                    ['type' => 'Townhouse', 'price' => 7800000, 'resale_price' => null],
                    ['type' => 'Studio', 'price' => 1800000, 'resale_price' => null],
                ],
            ],
            [
                'name' => 'بالم هيلز أكتوبر',
                'developer' => 'بالم هيلز للتعمير',
                'city' => '6th of October',
                'status' => CompletionStatusEnum::Completed,
                'delivery_date' => '2022-09-01',
                'description' => [
                    'en' => 'Palm Hills October is a premier residential community in 6th of October City with world-class amenities and golf courses.',
                    'ar' => 'بالم هيلز أكتوبر هو مجتمع سكني متميز في مدينة السادس من أكتوبر مع مرافق عالمية وملاعب جولف.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 2800000, 'resale_price' => 3400000],
                    ['type' => 'Villa', 'price' => 20000000, 'resale_price' => 24000000],
                    ['type' => 'Townhouse', 'price' => 9000000, 'resale_price' => 11000000],
                    ['type' => 'Apartment', 'price' => 3600000, 'resale_price' => 4300000],
                    ['type' => 'Penthouse', 'price' => 8500000, 'resale_price' => 10200000],
                ],
            ],
            [
                'name' => 'بادية',
                'developer' => 'بالم هيلز للتعمير',
                'city' => '6th of October',
                'status' => CompletionStatusEnum::UnderConstruction,
                'delivery_date' => '2029-01-01',
                'description' => [
                    'en' => 'Badya is a smart city concept by Palm Hills in 6th of October, integrating technology with urban planning for a sustainable lifestyle.',
                    'ar' => 'بادية هي مدينة ذكية من بالم هيلز في السادس من أكتوبر، تدمج التكنولوجيا مع التخطيط العمراني لنمط حياة مستدام.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 2500000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 3200000, 'resale_price' => null],
                    ['type' => 'Villa', 'price' => 14000000, 'resale_price' => null],
                    ['type' => 'Townhouse', 'price' => 6500000, 'resale_price' => null],
                    ['type' => 'Studio', 'price' => 1500000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 4100000, 'resale_price' => null],
                ],
            ],
            [
                'name' => 'ماونتن فيو آي سيتي',
                'developer' => 'ماونتن فيو',
                'city' => 'New Cairo',
                'status' => CompletionStatusEnum::UnderConstruction,
                'delivery_date' => '2027-06-01',
                'description' => [
                    'en' => 'Mountain View iCity is an innovative residential project in New Cairo featuring unique park-in-park design concept.',
                    'ar' => 'ماونتن فيو آي سيتي مشروع سكني مبتكر في القاهرة الجديدة يتميز بمفهوم تصميم حديقة داخل حديقة.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 3000000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 4200000, 'resale_price' => null],
                    ['type' => 'Villa', 'price' => 18000000, 'resale_price' => null],
                    ['type' => 'Townhouse', 'price' => 8000000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 5500000, 'resale_price' => null],
                ],
            ],
            [
                'name' => 'زيد الشيخ زايد',
                'developer' => 'أورا ديفلوبرز',
                'city' => 'Sheikh Zayed',
                'status' => CompletionStatusEnum::UnderConstruction,
                'delivery_date' => '2028-03-01',
                'description' => [
                    'en' => 'ZED Sheikh Zayed by Ora Developers is a luxury compound offering premium residences with state-of-the-art amenities.',
                    'ar' => 'زيد الشيخ زايد من أورا ديفلوبرز كمبوند فاخر يقدم وحدات سكنية متميزة مع مرافق حديثة.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 3800000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 5200000, 'resale_price' => null],
                    ['type' => 'Villa', 'price' => 25000000, 'resale_price' => null],
                    ['type' => 'Penthouse', 'price' => 15000000, 'resale_price' => null],
                    ['type' => 'Townhouse', 'price' => 11000000, 'resale_price' => null],
                ],
            ],
            [
                'name' => 'المونت جلالة',
                'developer' => 'تطوير مصر',
                'city' => 'Hurghada',
                'status' => CompletionStatusEnum::UnderConstruction,
                'delivery_date' => '2027-09-01',
                'description' => [
                    'en' => 'Il Monte Galala is a mountain-top resort by Tatweer Misr in Ain Sokhna, offering breathtaking views of the Red Sea.',
                    'ar' => 'المونت جلالة منتجع جبلي من تطوير مصر في العين السخنة، يوفر إطلالات خلابة على البحر الأحمر.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 2800000, 'resale_price' => null],
                    ['type' => 'Villa', 'price' => 12000000, 'resale_price' => null],
                    ['type' => 'Townhouse', 'price' => 6000000, 'resale_price' => null],
                    ['type' => 'Studio', 'price' => 1200000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 4000000, 'resale_price' => null],
                ],
            ],
            [
                'name' => 'سوان ليك ريزيدنس',
                'developer' => 'حسن علام العقارية',
                'city' => 'New Cairo',
                'status' => CompletionStatusEnum::Completed,
                'delivery_date' => '2023-12-01',
                'description' => [
                    'en' => 'Swan Lake Residences by Hassan Allam Properties in New Cairo, a completed luxury compound with premium finishing.',
                    'ar' => 'سوان ليك ريزيدنس من حسن علام العقارية في القاهرة الجديدة، كمبوند فاخر مكتمل بتشطيبات متميزة.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 4000000, 'resale_price' => 4800000],
                    ['type' => 'Apartment', 'price' => 5500000, 'resale_price' => 6600000],
                    ['type' => 'Villa', 'price' => 20000000, 'resale_price' => 24000000],
                    ['type' => 'Townhouse', 'price' => 10000000, 'resale_price' => 12000000],
                ],
            ],
            [
                'name' => 'تاج سيتي',
                'developer' => 'مدينة نصر للإسكان والتعمير',
                'city' => 'Nasr City',
                'status' => CompletionStatusEnum::UnderConstruction,
                'delivery_date' => '2028-12-01',
                'description' => [
                    'en' => 'Taj City by Nasr City Housing is a mega mixed-use development in New Cairo with residential, commercial, and entertainment zones.',
                    'ar' => 'تاج سيتي من مدينة نصر للإسكان مشروع ضخم متعدد الاستخدامات في القاهرة الجديدة مع مناطق سكنية وتجارية وترفيهية.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 2600000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 3900000, 'resale_price' => null],
                    ['type' => 'Villa', 'price' => 15000000, 'resale_price' => null],
                    ['type' => 'Townhouse', 'price' => 7500000, 'resale_price' => null],
                    ['type' => 'Studio', 'price' => 1600000, 'resale_price' => null],
                    ['type' => 'Penthouse', 'price' => 10000000, 'resale_price' => null],
                ],
            ],
            [
                'name' => 'هايد بارك',
                'developer' => 'هايد بارك للتطوير العقاري',
                'city' => 'El Shorouk',
                'status' => CompletionStatusEnum::UnderConstruction,
                'delivery_date' => '2027-03-01',
                'description' => [
                    'en' => 'Hyde Park New Cairo is one of the largest residential projects in New Cairo, spanning over 6 million sqm.',
                    'ar' => 'هايد بارك القاهرة الجديدة من أكبر المشاريع السكنية في القاهرة الجديدة، يمتد على مساحة أكثر من 6 مليون متر مربع.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 2200000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 3400000, 'resale_price' => null],
                    ['type' => 'Villa', 'price' => 13000000, 'resale_price' => null],
                    ['type' => 'Townhouse', 'price' => 6800000, 'resale_price' => null],
                    ['type' => 'Apartment', 'price' => 4600000, 'resale_price' => null],
                ],
            ],
            [
                'name' => 'نورث إيدج تاورز',
                'developer' => 'سيتي إيدج للتطوير العقاري',
                'city' => 'El Alamein',
                'status' => CompletionStatusEnum::Completed,
                'delivery_date' => '2024-01-01',
                'description' => [
                    'en' => 'North Edge Towers by City Edge in New Alamein, a completed beachfront tower project with premium sea views.',
                    'ar' => 'نورث إيدج تاورز من سيتي إيدج في العلمين الجديدة، مشروع أبراج شاطئية مكتمل بإطلالات بحرية متميزة.',
                ],
                'units' => [
                    ['type' => 'Apartment', 'price' => 5000000, 'resale_price' => 6000000],
                    ['type' => 'Apartment', 'price' => 7500000, 'resale_price' => 9000000],
                    ['type' => 'Penthouse', 'price' => 20000000, 'resale_price' => 24000000],
                    ['type' => 'Studio', 'price' => 2500000, 'resale_price' => 3000000],
                ],
            ],
        ];

        $totalUnits = array_sum(array_map(fn (array $compoundData): int => count($compoundData['units']), $compounds));
        $propertyCreatedAt = now()->startOfSecond()->subSeconds($totalUnits);
        $propertyCreatedAtOffset = 0;

        foreach ($compounds as $compoundData) {
            $developer = $developers->get($compoundData['developer']);

            if (! $developer) {
                continue;
            }

            $cityId = $cities[$compoundData['city']] ?? null;

            $compound = Compound::updateOrCreate(
                ['name' => $compoundData['name'], 'developer_id' => $developer->id],
                [
                    'city_id' => $cityId,
                    'completion_status' => $compoundData['status'],
                    'description' => $compoundData['description'],
                    'delivery_date' => $compoundData['delivery_date'],
                ],
            );

            foreach ($compoundData['units'] as $index => $unitData) {
                $propertyType = $propertyTypes->get($unitData['type']);

                if (! $propertyType) {
                    continue;
                }

                $property = Property::updateOrCreate(
                    [
                        'compound_id' => $compound->id,
                        'property_type_id' => $propertyType->id,
                        'price' => $unitData['price'],
                    ],
                    [
                        'name' => [
                            'en' => $unitData['type'].' Unit '.($index + 1).' - '.$compoundData['name'],
                            'ar' => $unitData['type'].' وحدة '.($index + 1).' - '.$compoundData['name'],
                        ],
                        'status' => 'active',
                        'address' => $compoundData['city'],
                        'resale_price' => $unitData['resale_price'],
                        'city_id' => $cityId,
                    ],
                );

                $propertyTimestamp = $propertyCreatedAt->copy()->addSeconds($propertyCreatedAtOffset++);
                $property->forceFill([
                    'created_at' => $propertyTimestamp,
                    'updated_at' => $propertyTimestamp,
                ])->saveQuietly();
            }
        }
    }
}
