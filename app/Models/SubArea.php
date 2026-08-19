<?php

declare(strict_types=1);

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class City extends Model implements HasMedia
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_IMAGE = 'city_image';

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'name',
        'state_id',
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

    public function state(): BelongsTo
    {
        return $this->belongsTo(State::class);
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
