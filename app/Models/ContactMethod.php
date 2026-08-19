<?php

namespace App\Models;

use App\Enums\ContactMethodTypeEnum;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ContactMethod extends Model
{
    use HasFactory;

    protected $fillable = [
        'contactable_type',
        'contactable_id',
        'type',
        'country_code',
        'number',
        'is_primary',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'type' => ContactMethodTypeEnum::class,
            'is_primary' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function contactable(): MorphTo
    {
        return $this->morphTo();
    }

    public function scopeType(Builder $query, ContactMethodTypeEnum|string $type): void
    {
        $query->where('type', $type instanceof ContactMethodTypeEnum ? $type->value : $type);
    }

    public function getFormattedNumberAttribute(): string
    {
        return $this->country_code.$this->number;
    }
}
