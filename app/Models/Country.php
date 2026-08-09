<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\Translatable\HasTranslations;

class Country extends Model implements HasMedia
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasFactory;
    use HasTranslations;
    use InteractsWithMedia;

    public const MEDIA_COLLECTION_FLAG = 'country_flag';

    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'code',
        'phone_code',
    ];

    public function getFlagUrlAttribute(): ?string
    {
        $mediaUrl = $this->getFirstMediaUrl(self::MEDIA_COLLECTION_FLAG);

        if ($mediaUrl !== '') {
            return $mediaUrl;
        }

        $path = 'flags/'.strtolower($this->code).'.png';

        return file_exists(public_path($path)) ? asset($path) : null;
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
