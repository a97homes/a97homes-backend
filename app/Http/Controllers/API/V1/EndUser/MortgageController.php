<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Mortgage\CalculateMortgageRequest;
use Illuminate\Http\JsonResponse;

class MortgageController extends Controller
{
    /**
     * Calculate the monthly installment and total cost for a property.
     *
     * Uses the standard amortisation formula when an annual interest
     * rate is provided (> 0):
     *     M = P * r / (1 - (1 + r)^-n)
     * where P = financed amount, r = monthly rate, n = number of months.
     *
     * When annual_interest_rate is omitted or 0 the calculator assumes
     * a zero-interest developer plan and returns a flat split
     * (financed / months).
     */
    public function calculate(CalculateMortgageRequest $request): JsonResponse
    {
        $price = (float) $request->validated('price');
        $downPaymentPercentage = (float) $request->validated('down_payment_percentage');
        $years = (int) $request->validated('years');
        $annualInterestRate = (float) ($request->validated('annual_interest_rate') ?? 0);

        $downPaymentAmount = round($price * $downPaymentPercentage / 100, 2);
        $financed = round($price - $downPaymentAmount, 2);
        $months = $years * 12;

        if ($annualInterestRate <= 0) {
            $monthlyPayment = $months > 0 ? round($financed / $months, 2) : 0.0;
            $totalInterest = 0.0;
        } else {
            $monthlyRate = ($annualInterestRate / 100) / 12;
            $monthlyPayment = round(
                $financed * $monthlyRate / (1 - pow(1 + $monthlyRate, -$months)),
                2,
            );
            $totalInterest = round(($monthlyPayment * $months) - $financed, 2);
        }

        $totalCost = round($downPaymentAmount + ($monthlyPayment * $months), 2);

        return $this->ok(data: [
            'inputs' => [
                'price' => $price,
                'down_payment_percentage' => $downPaymentPercentage,
                'years' => $years,
                'annual_interest_rate' => $annualInterestRate,
            ],
            'down_payment_amount' => $downPaymentAmount,
            'financed_amount' => $financed,
            'months' => $months,
            'monthly_payment' => $monthlyPayment,
            'total_interest' => $totalInterest,
            'total_cost' => $totalCost,
        ]);
    }
}
