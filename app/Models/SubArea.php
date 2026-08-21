<?php

declare(strict_types=1);

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Filters\UpdatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class SubArea extends Model implements HasMedia
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use UpdatedAtFilter;

    public const MEDIA_COLLECTION_IMAGE = 'sub_area_image';

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'area_id',
        'description',
        'latitude',
        'longitude',
    ];

    protected function casts(): array
    {
        return [
            'latitude' => 'decimal:7',
            'longitude' => 'decimal:7',
        ];
    }

    /**
     * Filter sub areas by the country of their parent area.
     *
     * @param  int|string|array<int, int|string>  $value
     */
    public function scopeCountryId(Builder $query, int|string|array $value): void
    {
        $query->whereHas('area', fn (Builder $areas) => $areas->whereIn('country_id', (array) $value));
    }

    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function compounds(): HasMany
    {
        return $this->hasMany(Compound::class);
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
}
