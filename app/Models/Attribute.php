<?php

namespace App\Models;

use App\Enums\Attribute\AttributeTypeEnum;
use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = ['name', 'type', 'unit_id', 'is_filterable'];

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'attribute_property')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function propertyTypes(): BelongsToMany
    {
        return $this->belongsToMany(PropertyType::class, 'attribute_property_type')
            ->withPivot('is_required')
            ->withTimestamps();
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    public function options(): HasMany
    {
        return $this->hasMany(AttributeOption::class)->orderBy('sort_order');
    }

    public function activeOptions(): HasMany
    {
        return $this->hasMany(AttributeOption::class)
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    public function isSelectType(): bool
    {
        return $this->type === AttributeTypeEnum::Select;
    }

    protected function casts(): array
    {
        return [
            'type' => AttributeTypeEnum::class,
            'is_filterable' => 'boolean',
        ];
    }
}
