<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Country extends Model
{
    use CreatedAtFilter;
    use HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = [
        'name',
        'code',
    ];

    public function states(): HasMany
    {
        return $this->hasMany(State::class);
    }
}
