<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class PropertyType extends Model
{
    use CreatedAtFilter;
    use HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = ['name'];

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_property_type');
    }
}
