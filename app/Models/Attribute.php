<?php

namespace App\Models;

use App\Enums\Attribute\AttributeTypeEnum;
use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Attribute extends Model
{
    use CreatedAtFilter;
    use HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = ['name', 'type', 'unit_id'];

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'attribute_property');
    }

    public function propertyTypes(): BelongsToMany
    {
        return $this->belongsToMany(PropertyType::class, 'attribute_property_type');
    }

    public function unit(): BelongsTo
    {
        return $this->belongsTo(Unit::class);
    }

    protected $casts = [
        'type' => AttributeTypeEnum::class,
    ];
}
