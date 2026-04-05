<?php

namespace Database\Seeders;

use App\Models\Compound;
use App\Models\Offer;
use Illuminate\Database\Seeder;

class OfferSeeder extends Seeder
{
    public function run(): void
    {
        $offers = [
            [
                'compound_name' => 'أبتاون كايرو',
                'offers' => [
                    [
                        'installment_years' => 8,
                        'down_payment_percentage' => 10.00,
                        'monthly_payment' => 52500,
                        'description' => [
                            'en' => '10% down payment with 8-year installment plan',
                            'ar' => '10% مقدم وتقسيط على 8 سنوات',
                        ],
                    ],
                    [
                        'installment_years' => 6,
                        'down_payment_percentage' => 15.00,
                        'monthly_payment' => 63000,
                        'description' => [
                            'en' => '15% down payment with 6-year installment plan',
                            'ar' => '15% مقدم وتقسيط على 6 سنوات',
                        ],
                    ],
                    [
                        'installment_years' => 10,
                        'down_payment_percentage' => 5.00,
                        'monthly_payment' => 44100,
                        'description' => [
                            'en' => '5% down payment with 10-year installment plan',
                            'ar' => '5% مقدم وتقسيط على 10 سنوات',
                        ],
                    ],
                ],
            ],
            [
                'compound_name' => 'فيليت',
                'offers' => [
                    [
                        'installment_years' => 7,
                        'down_payment_percentage' => 10.00,
                        'monthly_payment' => 34285,
                        'description' => [
                            'en' => '10% down payment with 7-year installment plan',
                            'ar' => '10% مقدم وتقسيط على 7 سنوات',
                        ],
                    ],
                    [
                        'installment_years' => 5,
                        'down_payment_percentage' => 20.00,
                        'monthly_payment' => 42666,
                        'description' => [
                            'en' => '20% down payment with 5-year installment plan',
                            'ar' => '20% مقدم وتقسيط على 5 سنوات',
                        ],
                    ],
                ],
            ],
            [
                'compound_name' => 'بادية',
                'offers' => [
                    [
                        'installment_years' => 10,
                        'down_payment_percentage' => 5.00,
                        'monthly_payment' => 19791,
                        'description' => [
                            'en' => '5% down payment with 10-year installment plan - limited time',
                            'ar' => '5% مقدم وتقسيط على 10 سنوات - عرض لفترة محدودة',
                        ],
                    ],
                    [
                        'installment_years' => 8,
                        'down_payment_percentage' => 10.00,
                        'monthly_payment' => 23437,
                        'description' => [
                            'en' => '10% down payment with 8-year installment plan',
                            'ar' => '10% مقدم وتقسيط على 8 سنوات',
                        ],
                    ],
                    [
                        'installment_years' => 6,
                        'down_payment_percentage' => 15.00,
                        'monthly_payment' => 29513,
                        'description' => [
                            'en' => '15% down payment with 6-year installment plan',
                            'ar' => '15% مقدم وتقسيط على 6 سنوات',
                        ],
                    ],
                ],
            ],
            [
                'compound_name' => 'ماونتن فيو آي سيتي',
                'offers' => [
                    [
                        'installment_years' => 8,
                        'down_payment_percentage' => 10.00,
                        'monthly_payment' => 28125,
                        'description' => [
                            'en' => '10% down payment over 8 years - 0% interest',
                            'ar' => '10% مقدم على 8 سنوات - بدون فوائد',
                        ],
                    ],
                    [
                        'installment_years' => 10,
                        'down_payment_percentage' => 5.00,
                        'monthly_payment' => 23750,
                        'description' => [
                            'en' => '5% down payment over 10 years',
                            'ar' => '5% مقدم على 10 سنوات',
                        ],
                    ],
                ],
            ],
            [
                'compound_name' => 'زيد الشيخ زايد',
                'offers' => [
                    [
                        'installment_years' => 9,
                        'down_payment_percentage' => 5.00,
                        'monthly_payment' => 33425,
                        'description' => [
                            'en' => '5% down payment with 9-year installment plan',
                            'ar' => '5% مقدم وتقسيط على 9 سنوات',
                        ],
                    ],
                    [
                        'installment_years' => 7,
                        'down_payment_percentage' => 10.00,
                        'monthly_payment' => 40714,
                        'description' => [
                            'en' => '10% down payment with 7-year installment plan',
                            'ar' => '10% مقدم وتقسيط على 7 سنوات',
                        ],
                    ],
                ],
            ],
            [
                'compound_name' => 'المونت جلالة',
                'offers' => [
                    [
                        'installment_years' => 8,
                        'down_payment_percentage' => 10.00,
                        'monthly_payment' => 26250,
                        'description' => [
                            'en' => '10% down payment over 8 years with club membership included',
                            'ar' => '10% مقدم على 8 سنوات مع عضوية النادي مجانية',
                        ],
                    ],
                ],
            ],
            [
                'compound_name' => 'هايد بارك',
                'offers' => [
                    [
                        'installment_years' => 10,
                        'down_payment_percentage' => 5.00,
                        'monthly_payment' => 17416,
                        'description' => [
                            'en' => '5% down payment with 10-year installment plan - Ramadan offer',
                            'ar' => '5% مقدم وتقسيط على 10 سنوات - عرض رمضان',
                        ],
                    ],
                    [
                        'installment_years' => 7,
                        'down_payment_percentage' => 15.00,
                        'monthly_payment' => 22261,
                        'description' => [
                            'en' => '15% down payment with 7-year installment plan',
                            'ar' => '15% مقدم وتقسيط على 7 سنوات',
                        ],
                    ],
                    [
                        'installment_years' => 5,
                        'down_payment_percentage' => 25.00,
                        'monthly_payment' => 27500,
                        'description' => [
                            'en' => '25% down payment with 5-year plan - best value',
                            'ar' => '25% مقدم على 5 سنوات - أفضل قيمة',
                        ],
                    ],
                ],
            ],
            [
                'compound_name' => 'تاج سيتي',
                'offers' => [
                    [
                        'installment_years' => 10,
                        'down_payment_percentage' => 10.00,
                        'monthly_payment' => 19500,
                        'description' => [
                            'en' => '10% down payment over 10 years',
                            'ar' => '10% مقدم على 10 سنوات',
                        ],
                    ],
                    [
                        'installment_years' => 8,
                        'down_payment_percentage' => 15.00,
                        'monthly_payment' => 23010,
                        'description' => [
                            'en' => '15% down payment over 8 years with maintenance included',
                            'ar' => '15% مقدم على 8 سنوات شامل الصيانة',
                        ],
                    ],
                ],
            ],
        ];

        foreach ($offers as $data) {
            $compound = Compound::where('name', $data['compound_name'])->first();

            if (! $compound) {
                continue;
            }

            foreach ($data['offers'] as $offerData) {
                Offer::updateOrCreate(
                    [
                        'compound_id' => $compound->id,
                        'installment_years' => $offerData['installment_years'],
                        'down_payment_percentage' => $offerData['down_payment_percentage'],
                    ],
                    [
                        'monthly_payment' => $offerData['monthly_payment'],
                        'description' => $offerData['description'],
                        'is_active' => true,
                    ],
                );
            }
        }
    }
}
