<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasFactory;
    use HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'code',
        'phone_code',
    ];

    public function getFlagUrlAttribute(): ?string
    {
        $path = 'flags/'.strtolower($this->code).'.png';

        return file_exists(public_path($path)) ? asset($path) : null;
    }

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
