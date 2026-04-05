<?php

namespace App\Models;

use App\Enums\CompletionStatusEnum;
use App\Filters\CreatedAtFilter;
use App\Models\User\User;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Compound extends Model implements HasMedia
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasTranslations;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_IMAGE = 'compound_image';

    protected $fillable = [
        'name',
        'developer_id',
        'city_id',
        'completion_status',
        'description',
        'delivery_date',
        'is_featured',
    ];

    public array $translatable = ['description'];

    protected function casts(): array
    {
        return [
            'completion_status' => CompletionStatusEnum::class,
            'delivery_date' => 'date',
            'is_featured' => 'boolean',
        ];
    }

    /**
     * Override the Arabic search scope since `name` is a plain string, not JSON.
     */
    public function scopeSearchByName(Builder $query, string $value): void
    {
        $normalizedValue = $this->normalizeArabicText($value);

        $query->where(function ($q) use ($normalizedValue, $value) {
            $q->where('name', 'LIKE', "%{$value}%")
                ->orWhere('name', 'LIKE', "%{$normalizedValue}%");
        });
    }

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function properties(): HasMany
    {
        return $this->hasMany(Property::class);
    }

    public function discounts(): HasMany
    {
        return $this->hasMany(Discount::class);
    }

    public function offers(): HasMany
    {
        return $this->hasMany(Offer::class);
    }

    public function activeOffers(): HasMany
    {
        return $this->hasMany(Offer::class)->where('is_active', true);
    }

    public function activeDiscount(): HasOne
    {
        return $this->hasOne(Discount::class)
            ->where('is_active', true)
            ->where('start_date', '<=', now()->toDateString())
            ->where('end_date', '>=', now()->toDateString())
            ->latest('percentage');
    }

    public function favorites(): HasMany
    {
        return $this->hasMany(Favorite::class);
    }

    public function favoritedByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    public function isCompleted(): bool
    {
        return $this->completion_status === CompletionStatusEnum::Completed;
    }

    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('completion_status', CompletionStatusEnum::Completed);
    }

    public function scopeWithFavoritesForUser(Builder $query, ?int $userId): void
    {
        if ($userId) {
            $query->withCount([
                'favorites as is_favorited' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                },
            ]);
        }
    }
}
