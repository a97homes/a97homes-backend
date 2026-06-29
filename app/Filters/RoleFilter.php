<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class RoleFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property)
    {
        $ids = is_array($value) ? $value : [$value];

        return $query->whereHas('roles', fn ($q) => $q->whereIn('id', $ids));
    }
}
