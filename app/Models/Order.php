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
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

#[ObservedBy([OrderObserver::class])]

class Order extends Model
{
    use CreatedAtFilter;

    protected $fillable = ['status', 'phone', 'name', 'description', 'city_id', 'property_type_id'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    #[Scope]
    protected function pending(Builder $query): Builder
    {
        return $query->where('status', OrderStatusEnum::PENDING);
    }

    protected $casts = [
        'status' => OrderStatusEnum::class,
        'phone' => E164PhoneNumberCast::class,
    ];
}
