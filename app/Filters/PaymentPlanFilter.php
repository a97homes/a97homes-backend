<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;

class PaymentPlanFilter
{
    /**
     * Apply payment plan filters on a property query via compound.activeOffers.
     *
     * Supported params:
     * - down_payment_min / down_payment_max (percentage, e.g. 10 = 10%)
     * - monthly_payment_min / monthly_payment_max (integer amount)
     * - installment_years (exact match or array)
     */
    public static function apply(Builder $query, array $params): void
    {
        $downMin = $params['down_payment_min'] ?? null;
        $downMax = $params['down_payment_max'] ?? null;
        $monthlyMin = $params['monthly_payment_min'] ?? null;
        $monthlyMax = $params['monthly_payment_max'] ?? null;
        $installmentYears = $params['installment_years'] ?? null;

        $hasAny = $downMin !== null || $downMax !== null
            || $monthlyMin !== null || $monthlyMax !== null
            || $installmentYears !== null;

        if (! $hasAny) {
            return;
        }

        $query->whereHas('compound.activeOffers', function (Builder $q) use (
            $downMin, $downMax, $monthlyMin, $monthlyMax, $installmentYears
        ) {
            if ($downMin !== null) {
                $q->where('down_payment_percentage', '>=', (float) $downMin);
            }

            if ($downMax !== null) {
                $q->where('down_payment_percentage', '<=', (float) $downMax);
            }

            if ($monthlyMin !== null) {
                $q->where('monthly_payment', '>=', (int) $monthlyMin);
            }

            if ($monthlyMax !== null) {
                $q->where('monthly_payment', '<=', (int) $monthlyMax);
            }

            if ($installmentYears !== null) {
                $years = is_array($installmentYears) ? $installmentYears : [$installmentYears];
                $q->whereIn('installment_years', array_map('intval', $years));
            }
        });
    }
}
