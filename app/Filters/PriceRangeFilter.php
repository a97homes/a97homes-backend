<?php

namespace App\Filters;

use App\Models\Property;
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

        $minPriceSubquery = Property::selectRaw('MIN(price)')
            ->whereColumn('compound_id', 'compounds.id');

        if ($min !== null) {
            $query->where($minPriceSubquery, '>=', (int) $min);
        }

        if ($max !== null) {
            $query->where($minPriceSubquery, '<=', (int) $max);
        }
    }
}
