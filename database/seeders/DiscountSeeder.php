<?php

namespace Database\Seeders;

use App\Models\Compound;
use App\Models\Discount;
use Illuminate\Database\Seeder;

class DiscountSeeder extends Seeder
{
    public function run(): void
    {
        $compounds = Compound::all();

        $discountData = [
            // Active discounts
            [
                'compound_name' => 'أبتاون كايرو',
                'percentage' => 10.00,
                'start_date' => now()->subDays(15)->toDateString(),
                'end_date' => now()->addDays(45)->toDateString(),
                'is_active' => true,
            ],
            [
                'compound_name' => 'بادية',
                'percentage' => 15.00,
                'start_date' => now()->subDays(5)->toDateString(),
                'end_date' => now()->addDays(60)->toDateString(),
                'is_active' => true,
            ],
            [
                'compound_name' => 'ماونتن فيو آي سيتي',
                'percentage' => 8.00,
                'start_date' => now()->subDays(10)->toDateString(),
                'end_date' => now()->addDays(30)->toDateString(),
                'is_active' => true,
            ],
            [
                'compound_name' => 'هايد بارك',
                'percentage' => 12.00,
                'start_date' => now()->subDays(3)->toDateString(),
                'end_date' => now()->addDays(90)->toDateString(),
                'is_active' => true,
            ],
            // Expired discounts
            [
                'compound_name' => 'مراسي',
                'percentage' => 5.00,
                'start_date' => now()->subDays(90)->toDateString(),
                'end_date' => now()->subDays(30)->toDateString(),
                'is_active' => true,
            ],
            [
                'compound_name' => 'أليجريا',
                'percentage' => 7.00,
                'start_date' => now()->subDays(60)->toDateString(),
                'end_date' => now()->subDays(10)->toDateString(),
                'is_active' => false,
            ],
        ];

        foreach ($discountData as $data) {
            $compound = $compounds->firstWhere('name', $data['compound_name']);

            if (! $compound) {
                continue;
            }

            Discount::updateOrCreate(
                ['compound_id' => $compound->id, 'percentage' => $data['percentage']],
                [
                    'start_date' => $data['start_date'],
                    'end_date' => $data['end_date'],
                    'is_active' => $data['is_active'],
                ],
            );
        }
    }
}
