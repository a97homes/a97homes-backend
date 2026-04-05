<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Offer extends Model
{
    use HasTranslations;

    public array $translatable = ['description'];

    protected $fillable = [
        'compound_id',
        'installment_years',
        'down_payment_percentage',
        'monthly_payment',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'down_payment_percentage' => 'decimal:2',
            'monthly_payment' => 'integer',
            'installment_years' => 'integer',
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
