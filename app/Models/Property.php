<?php

namespace App\Models;

use App\Enums\PropertyStatusEnum;
use App\Filters\CreatedAtFilter;
use App\Models\User\User;
use App\Observers\PropertyObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
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

    protected $fillable = ['name', 'property_type_id', 'city_id', 'status', 'order_id', 'latitude', 'longitude', 'compound_id', 'address', 'price', 'resale_price'];

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
        return $this->belongsToMany(Attribute::class, 'attribute_property')
            ->withPivot('value')
            ->withTimestamps();
    }

    public function selectedOptions(): BelongsToMany
    {
        return $this->belongsToMany(AttributeOption::class, 'attribute_property_option')
            ->withPivot('attribute_id')
            ->withTimestamps();
    }

    public function compound(): BelongsTo
    {
        return $this->belongsTo(Compound::class);
    }

    public function propertyFavorites(): HasMany
    {
        return $this->hasMany(PropertyFavorite::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'property_favorites')
            ->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'status' => PropertyStatusEnum::class,
            'price' => 'decimal:2',
            'resale_price' => 'decimal:2',
        ];
    }
}
