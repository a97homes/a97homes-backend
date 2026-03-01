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
        $query->whereHas('properties', function (Builder $q) use ($value) {
            $q->whereHas('attributes', function (Builder $attrQuery) use ($value) {
                $attrQuery->where('attributes.slug', $this->attributeSlug)
                    ->where('attribute_property.value', $value);
            });
        });
    }
}
