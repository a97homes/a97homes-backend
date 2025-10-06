<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Property extends Model
{
    use CreatedAtFilter;
    use HasTranslations;

    protected $fillable = ['name', 'property_type_id', 'city_id'];

    public array $translatable = ['name'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class, 'city_id');
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class, 'property_type_id');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);

    }
}
