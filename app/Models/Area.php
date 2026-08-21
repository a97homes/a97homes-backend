<?php

declare(strict_types=1);

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Filters\UpdatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Area extends Model implements HasMedia
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;
    use UpdatedAtFilter;

    public const MEDIA_COLLECTION_COVER = 'area_cover';

    public const MEDIA_COLLECTION_LOGO = 'area_logo';

    /**
     * Media collections the admin media endpoints accept.
     *
     * @var array<int, string>
     */
    public const MEDIA_COLLECTIONS = [
        self::MEDIA_COLLECTION_COVER,
        self::MEDIA_COLLECTION_LOGO,
    ];

    public array $translatable = ['name', 'about'];

    protected $fillable = ['name', 'about', 'country_id'];

    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class);
    }

    public function subAreas(): HasMany
    {
        return $this->hasMany(SubArea::class);
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(self::MEDIA_COLLECTION_COVER) ?: null;
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(self::MEDIA_COLLECTION_LOGO) ?: null;
    }
}
