<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Filters\CreatedAtFilter;
use App\Models\User\User;
use App\Observers\OrderObserver;
use Illuminate\Contracts\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

#[ObservedBy([OrderObserver::class])]
class Order extends Model
{
    use CreatedAtFilter;

    protected $fillable = ['status'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    #[Scope]
    protected function pending(Builder $query): Builder
    {
        return $query->where('status', OrderStatusEnum::PENDING);
    }

    protected $casts = [
        'status' => OrderStatusEnum::class,
    ];
}
