<?php

namespace App\Models;

use App\Enums\PaymentPlanTypeEnum;
use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentPlan extends Model
{
    use CreatedAtFilter;

    protected $fillable = [
        'compound_id',
        'type',
        'installment_years',
        'down_payment',
        'monthly_payment',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'type' => PaymentPlanTypeEnum::class,
            'installment_years' => 'integer',
            'down_payment' => 'integer',
            'monthly_payment' => 'integer',
            'is_active' => 'boolean',
        ];
    }

    public function compound(): BelongsTo
    {
        return $this->belongsTo(Compound::class);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }
}
