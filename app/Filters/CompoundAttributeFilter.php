<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class CompoundAttributeFilter implements Filter
{
    public function __construct(
        private string $attributeSlug,
    ) {}

    public function __invoke(Builder $query, $value, string $property): void
    {
        if ($value === null || $value === '') {
            return;
        }

        $query->whereHas('properties', function (Builder $q) use ($value) {
            $q->whereHas('attributes', function (Builder $attrQuery) use ($value) {
                $attrQuery->where('attributes.slug', $this->attributeSlug)
                    ->whereNotNull('attribute_property.value')
                    ->whereRaw('CAST(attribute_property.value AS NUMERIC) = ?', [(int) $value]);
            });
        });
    }
}
