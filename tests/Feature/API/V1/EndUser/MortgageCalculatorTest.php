<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use Tests\TestCase;

class MortgageCalculatorTest extends TestCase
{
    public function test_zero_interest_plan_splits_financed_amount_evenly(): void
    {
        $response = $this->postJson('/api/V1/mortgage/calculate', [
            'price' => 1200000,
            'down_payment_percentage' => 20,
            'years' => 8,
            'annual_interest_rate' => 0,
        ]);

        $response->assertOk()
            ->assertJsonPath('data.down_payment_amount', 240000)
            ->assertJsonPath('data.financed_amount', 960000)
            ->assertJsonPath('data.months', 96)
            ->assertJsonPath('data.monthly_payment', 10000)
            ->assertJsonPath('data.total_interest', 0)
            ->assertJsonPath('data.total_cost', 1200000);
    }

    public function test_amortisation_with_positive_interest_rate(): void
    {
        // 100,000 financed @ 12% annual over 1 year should be ~8885/month, ~6619 interest
        $response = $this->postJson('/api/V1/mortgage/calculate', [
            'price' => 100000,
            'down_payment_percentage' => 0,
            'years' => 1,
            'annual_interest_rate' => 12,
        ]);

        $response->assertOk();
        $monthly = $response->json('data.monthly_payment');
        $interest = $response->json('data.total_interest');

        $this->assertGreaterThan(8800, $monthly);
        $this->assertLessThan(8900, $monthly);
        $this->assertGreaterThan(6500, $interest);
        $this->assertLessThan(6700, $interest);
    }

    public function test_validation_rejects_missing_fields(): void
    {
        $this->postJson('/api/V1/mortgage/calculate', [
            'price' => 1000000,
            // missing down_payment_percentage + years
        ])->assertUnprocessable();
    }

    public function test_validation_rejects_out_of_range_percentages(): void
    {
        $this->postJson('/api/V1/mortgage/calculate', [
            'price' => 1000000,
            'down_payment_percentage' => 150,
            'years' => 5,
        ])->assertUnprocessable();

        $this->postJson('/api/V1/mortgage/calculate', [
            'price' => 1000000,
            'down_payment_percentage' => 10,
            'years' => 100,
        ])->assertUnprocessable();
    }
}
