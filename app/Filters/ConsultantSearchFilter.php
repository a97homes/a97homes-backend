<?php

namespace App\Filters;

use Illuminate\Database\Eloquent\Builder;
use Spatie\QueryBuilder\Filters\Filter;

class ConsultantSearchFilter implements Filter
{
    public function __invoke(Builder $query, $value, string $property): void
    {
        $like = $query->getConnection()->getDriverName() === 'pgsql' ? 'ILIKE' : 'LIKE';

        $query->where(function (Builder $q) use ($value, $like) {
            $q->searchByName($value)
                ->orWhere('job_title', $like, "%{$value}%")
                ->orWhereHas('phones', function (Builder $phoneQuery) use ($value, $like) {
                    $phoneQuery->where('phone', $like, "%{$value}%");
                });
        });
    }
}
