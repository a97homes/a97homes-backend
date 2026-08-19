<?php

namespace App\Filters;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;

trait UpdatedAtFilter
{
    #[Scope]
    protected function updatedFrom(Builder $query, $date): Builder
    {
        return $query->whereDate('updated_at', '>=', Carbon::parse($date));
    }

    #[Scope]
    protected function updatedTo(Builder $query, $date): Builder
    {
        return $query->whereDate('updated_at', '<=', Carbon::parse($date));
    }
}
