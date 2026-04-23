<?php

namespace App\Filters;

use App\Enums\CompletionStatusEnum;
use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class CompoundDeliveryDateFilter implements Filter
{
    /**
     * Filter compounds directly by their delivery date / completion status.
     *
     * Accepts an array of values: ['delivered', '2025', '2026', '2027', '2028+']
     * - 'delivered' → completion_status = 'completed'
     * - '2025' → delivery_date in year 2025
     * - '2028+' → delivery_date >= 2028-01-01
     */
    public function __invoke(Builder $query, $value, string $property): void
    {
        $values = is_array($value) ? $value : [$value];

        $query->where(function (Builder $inner) use ($values) {
            foreach ($values as $v) {
                $v = trim((string) $v);

                if (strtolower($v) === 'delivered') {
                    $inner->orWhere('completion_status', CompletionStatusEnum::Completed->value);
                } elseif (str_ends_with($v, '+')) {
                    $year = (int) rtrim($v, '+');
                    $inner->orWhere('delivery_date', '>=', "{$year}-01-01");
                } elseif (is_numeric($v)) {
                    $year = (int) $v;
                    $inner->orWhereBetween('delivery_date', ["{$year}-01-01", "{$year}-12-31"]);
                }
            }
        });
    }
}
