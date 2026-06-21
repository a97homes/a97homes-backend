<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Developer extends Model implements HasMedia
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasFactory;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_LOGO = 'developer_logo';

    protected $fillable = ['name', 'about', 'is_active'];

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
     * Override the Arabic search scope since `name` is a plain string, not JSON.
     */
    public function scopeSearchByName(Builder $query, string $value): void
    {
        $normalizedValue = $this->normalizeArabicText($value);
        $driver = $query->getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            $query->where(function ($q) use ($normalizedValue, $value) {
                $q->whereRaw("REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(name, '[أإآ]', 'ا', 'g'), '[ة]', 'ه', 'g'), '[ي]', 'ى', 'g'), '[ؤ]', 'و', 'g'), '[ئ]', 'ي', 'g'), '[ء]', '', 'g'), '[ـ]', '', 'g') ILIKE ?", ["%{$normalizedValue}%"])
                    ->orWhere('name', 'ILIKE', "%{$value}%");
            });
        } elseif ($driver === 'sqlite') {
            $query->where(function ($q) use ($normalizedValue, $value) {
                $q->where('name', 'LIKE', "%{$value}%")
                    ->orWhere('name', 'LIKE', "%{$normalizedValue}%");
            });
        } else {
            $query->where(function ($q) use ($normalizedValue, $value) {
                $q->whereRaw("REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(name, '[أإآ]', 'ا'), '[ة]', 'ه'), '[ي]', 'ى'), '[ؤ]', 'و'), '[ئ]', 'ي'), '[ء]', ''), '[ـ]', '') LIKE ?", ["%{$normalizedValue}%"])
                    ->orWhere('name', 'LIKE', "%{$value}%");
            });
        }
    }

    public function compounds(): HasMany
    {
        return $this->hasMany(Compound::class);
    }

    public function getLogoUrlAttribute(): ?string
    {
        return $this->getFirstMediaUrl(self::MEDIA_COLLECTION_LOGO) ?: null;
    }
}
