<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;
use Spatie\Translatable\HasTranslations;

class PropertyType extends Model
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasSlug;
    use HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = ['name', 'slug'];

    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom(function ($model): string {
                return $model->getTranslation('name', 'en');
            })
            ->saveSlugsTo('slug');
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class, 'attribute_property_type')
            ->withPivot('is_required')
            ->withTimestamps();
    }
}
