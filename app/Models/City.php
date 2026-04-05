<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class City extends Model implements HasMedia
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasTranslations;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_IMAGE = 'city_image';

    public array $translatable = ['name'];

    protected $fillable = ['name', 'state_id'];

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }
}
