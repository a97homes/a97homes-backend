<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Consultant extends Model
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasFactory;

    protected $fillable = [
        'name',
        'job_title',
        'sales_percentage',
        'image',
        'cover_image',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'sales_percentage' => 'decimal:2',
            'is_featured' => 'boolean',
        ];
    }

    public function phones(): HasMany
    {
        return $this->hasMany(ConsultantPhone::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(ConsultantReview::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
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
        } else {
            $query->where(function ($q) use ($normalizedValue, $value) {
                $q->whereRaw("REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(name, '[أإآ]', 'ا'), '[ة]', 'ه'), '[ي]', 'ى'), '[ؤ]', 'و'), '[ئ]', 'ي'), '[ء]', ''), '[ـ]', '') LIKE ?", ["%{$normalizedValue}%"])
                    ->orWhere('name', 'LIKE', "%{$value}%");
            });
        }
    }

    public function scopeFeatured(Builder $query): Builder
    {
        return $query->where('is_featured', true);
    }
}
