<?php

namespace Database\Seeders;

use App\Models\Compound;
use App\Models\PaymentPlan;
use Illuminate\Database\Seeder;

class PaymentPlanSeeder extends Seeder
{
    public function run(): void
    {
        $compounds = Compound::all();

        if ($compounds->isEmpty()) {
            $this->command->warn('No compounds found. Skipping PaymentPlanSeeder.');

            return;
        }

        $paymentPlansData = [
            // Compound 1 - Premium compound (e.g., Mountain View iCity)
            [
                'compound_index' => 0,
                'plans' => [
                    [
                        'type' => 'original',
                        'installment_years' => 8,
                        'down_payment' => 1500000,
                        'monthly_payment' => 45000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 10,
                        'down_payment' => 1200000,
                        'monthly_payment' => 38000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 7,
                        'down_payment' => 2000000,
                        'monthly_payment' => 35000,
                    ],
                ],
            ],
            // Compound 2 - Mid-range compound (e.g., Sodic East)
            [
                'compound_index' => 1,
                'plans' => [
                    [
                        'type' => 'original',
                        'installment_years' => 10,
                        'down_payment' => 800000,
                        'monthly_payment' => 25000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 12,
                        'down_payment' => 600000,
                        'monthly_payment' => 22000,
                    ],
                ],
            ],
            // Compound 3 - Luxury compound (e.g., Palm Hills Katameya)
            [
                'compound_index' => 2,
                'plans' => [
                    [
                        'type' => 'original',
                        'installment_years' => 15,
                        'down_payment' => 3500000,
                        'monthly_payment' => 65000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 12,
                        'down_payment' => 4000000,
                        'monthly_payment' => 55000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 8,
                        'down_payment' => 5000000,
                        'monthly_payment' => 50000,
                    ],
                ],
            ],
            // Compound 4 - Affordable compound (e.g., Badya by Palm Hills)
            [
                'compound_index' => 3,
                'plans' => [
                    [
                        'type' => 'original',
                        'installment_years' => 10,
                        'down_payment' => 500000,
                        'monthly_payment' => 15000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 8,
                        'down_payment' => 400000,
                        'monthly_payment' => 18000,
                    ],
                ],
            ],
            // Compound 5 - New Cairo compound (e.g., Madinaty)
            [
                'compound_index' => 4,
                'plans' => [
                    [
                        'type' => 'original',
                        'installment_years' => 12,
                        'down_payment' => 1332440,
                        'monthly_payment' => 5016000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 15,
                        'down_payment' => 1332440,
                        'monthly_payment' => 4200000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 10,
                        'down_payment' => 1800000,
                        'monthly_payment' => 4500000,
                    ],
                ],
            ],
            // Compound 6 - North Coast compound
            [
                'compound_index' => 5,
                'plans' => [
                    [
                        'type' => 'original',
                        'installment_years' => 6,
                        'down_payment' => 950000,
                        'monthly_payment' => 32000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 8,
                        'down_payment' => 750000,
                        'monthly_payment' => 28000,
                    ],
                ],
            ],
            // Compound 7 - 6th October compound
            [
                'compound_index' => 6,
                'plans' => [
                    [
                        'type' => 'original',
                        'installment_years' => 7,
                        'down_payment' => 650000,
                        'monthly_payment' => 20000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 10,
                        'down_payment' => 500000,
                        'monthly_payment' => 17500,
                    ],
                ],
            ],
            // Compound 8 - Sheikh Zayed compound
            [
                'compound_index' => 7,
                'plans' => [
                    [
                        'type' => 'original',
                        'installment_years' => 9,
                        'down_payment' => 2200000,
                        'monthly_payment' => 42000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 12,
                        'down_payment' => 1800000,
                        'monthly_payment' => 37000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 6,
                        'down_payment' => 3000000,
                        'monthly_payment' => 35000,
                    ],
                ],
            ],
            // Compound 9 - New Administrative Capital
            [
                'compound_index' => 8,
                'plans' => [
                    [
                        'type' => 'original',
                        'installment_years' => 10,
                        'down_payment' => 1100000,
                        'monthly_payment' => 30000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 15,
                        'down_payment' => 850000,
                        'monthly_payment' => 25000,
                    ],
                ],
            ],
            // Compound 10 - El Shorouk compound
            [
                'compound_index' => 9,
                'plans' => [
                    [
                        'type' => 'original',
                        'installment_years' => 8,
                        'down_payment' => 450000,
                        'monthly_payment' => 12000,
                    ],
                    [
                        'type' => 'offer',
                        'installment_years' => 10,
                        'down_payment' => 350000,
                        'monthly_payment' => 10500,
                    ],
                ],
            ],
        ];

        foreach ($paymentPlansData as $compoundPlans) {
            $compound = $compounds->get($compoundPlans['compound_index']);

            if (! $compound) {
                continue;
            }

            foreach ($compoundPlans['plans'] as $plan) {
                PaymentPlan::updateOrCreate(
                    [
                        'compound_id' => $compound->id,
                        'type' => $plan['type'],
                        'installment_years' => $plan['installment_years'],
                    ],
                    [
                        'down_payment' => $plan['down_payment'],
                        'monthly_payment' => $plan['monthly_payment'],
                        'is_active' => true,
                    ]
                );
            }
        }
    }
}
