<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\ArticleTypeEnum;
use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Article extends Model implements HasMedia
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_COVER = 'article_cover';

    public const MEDIA_COLLECTION_GALLERY = 'article_gallery';

    public array $translatable = ['title', 'excerpt', 'body'];

    protected $fillable = [
        'slug',
        'type',
        'title',
        'excerpt',
        'body',
        'author',
        'published_at',
        'views_count',
        'is_featured',
    ];

    protected function casts(): array
    {
        return [
            'type' => ArticleTypeEnum::class,
            'published_at' => 'datetime',
            'views_count' => 'integer',
            'is_featured' => 'boolean',
        ];
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')->where('published_at', '<=', now());
    }

    public function scopeOfType(Builder $query, ArticleTypeEnum|string $type): Builder
    {
        return $query->where('type', $type instanceof ArticleTypeEnum ? $type->value : $type);
    }

    public function scopeSearchByName(Builder $query, string $value, ?string $locale = null): void
    {
        $driver = $query->getConnection()->getDriverName();
        $normalizedValue = $this->normalizeArabicText($value);

        if ($driver === 'pgsql') {
            $query->where(function ($q) use ($normalizedValue, $value) {
                $q->whereRaw("REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(title->>'ar', '[أإآ]', 'ا', 'g'), '[ة]', 'ه', 'g'), '[ي]', 'ى', 'g'), '[ؤ]', 'و', 'g'), '[ئ]', 'ي', 'g') ILIKE ?", ["%{$normalizedValue}%"])
                    ->orWhereRaw("title->>'en' ILIKE ?", ["%{$value}%"]);
            });
        } elseif ($driver === 'sqlite') {
            $query->where(function ($q) use ($normalizedValue, $value) {
                $q->whereRaw("json_extract(title, '$.ar') LIKE ?", ["%{$value}%"])
                    ->orWhereRaw("json_extract(title, '$.ar') LIKE ?", ["%{$normalizedValue}%"])
                    ->orWhereRaw("json_extract(title, '$.en') LIKE ?", ["%{$value}%"]);
            });
        } else {
            $query->where(function ($q) use ($normalizedValue, $value) {
                $q->whereRaw("REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(REGEXP_REPLACE(title->>'ar', '[أإآ]', 'ا'), '[ة]', 'ه'), '[ي]', 'ى'), '[ؤ]', 'و'), '[ئ]', 'ي') COLLATE utf8mb4_unicode_ci LIKE ? COLLATE utf8mb4_unicode_ci", ["%{$normalizedValue}%"])
                    ->orWhereRaw("title->>'en' COLLATE utf8mb4_unicode_ci LIKE ? COLLATE utf8mb4_unicode_ci", ["%{$value}%"]);
            });
        }
    }
}
