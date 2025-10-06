<?php

namespace App\Sorts;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Sorts\Sort;

class CreatedAtSort implements Sort
{
    public function __invoke(Builder $query, bool $descending, string $property)
    {
        match ($property) {
            'oldest' => $query->orderBy('id', 'asc'),
            'latest' => $query->orderBy('id', 'desc'),
            'default' => $query->orderBy('id', 'asc'),
        };
    }
}
