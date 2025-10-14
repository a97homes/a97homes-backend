<?php

namespace App\Models;

use App\Enums\PropertyStatusEnum;
use App\Filters\CreatedAtFilter;
use App\Observers\PropertyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

#[ObservedBy([PropertyObserver::class])]
class Property extends Model implements HasMedia
{
    use CreatedAtFilter;
    use HasTranslations;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_FILE = 'property_media';

    protected $fillable = ['name', 'property_type_id', 'city_id', 'status'];

    public array $translatable = ['name'];

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);

    }

    protected $casts = [
        'status' => PropertyStatusEnum::class,
    ];
}
