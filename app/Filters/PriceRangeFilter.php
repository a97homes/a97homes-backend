<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class PriceRangeFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        if (is_array($value)) {
            $min = $value['min'] ?? $value[0] ?? null;
            $max = $value['max'] ?? $value[1] ?? null;
        } else {
            return;
        }

        $query->whereHas('properties', function (Builder $q) use ($min, $max) {
            if ($min !== null) {
                $q->where('price', '>=', $min);
            }

            if ($max !== null) {
                $q->where('price', '<=', $max);
            }
        });
    }
}
