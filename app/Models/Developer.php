<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Developer extends Model implements HasMedia
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_LOGO = 'developer_logo';

    public const MEDIA_COLLECTION_BANNER = 'developer_banner';

    public array $translatable = ['name', 'about'];

    protected $fillable = ['name', 'about', 'whatsapp', 'phone', 'is_active'];

    /**
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    public function scopeActive(Builder $query): void
    {
        $query->where('is_active', true);
    }

    /**
     * @param  int|string|array<int, int|string>  $value
     */
    public function scopeAreaId(Builder $query, int|string|array $value): void
    {
        $query->whereHas('compounds', fn (Builder $compounds) => $compounds->whereIn('city_id', (array) $value));
    }

    public function scopeMinCompounds(Builder $query, int|string $value): void
    {
        $query->has('compounds', '>=', (int) $value);
    }

    public function scopeMinUnits(Builder $query, int|string $value): void
    {
        $query->has('properties', '>=', (int) $value);
    }

    public function compounds(): HasMany
    {
        return $this->hasMany(Compound::class);
    }

    public function properties(): HasManyThrough
    {
        return $this->hasManyThrough(Property::class, Compound::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function activeOffers(): HasMany
    {
        return $this->hasMany(Offer::class)->where('is_active', true);
    }

    public function faqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable');
    }

    public function activeFaqs(): MorphMany
    {
        return $this->morphMany(Faq::class, 'faqable')
            ->where('is_active', true)
            ->orderBy('sort_order');
    }

    /**
     * Areas the developer operates in: the cities of its compounds.
     *
     * Not distinct at the SQL level: PostgreSQL cannot `SELECT DISTINCT` over
     * the city's json columns. De-duplicate by id when presenting.
     */
    public function areas(): BelongsToMany
    {
        return $this->belongsToMany(City::class, 'compounds', 'developer_id', 'city_id');
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(self::MEDIA_COLLECTION_LOGO) ?: null;
    }

    public function getBannerUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(self::MEDIA_COLLECTION_BANNER) ?: null;
    }
}
