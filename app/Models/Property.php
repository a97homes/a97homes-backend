<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class Property extends Model
{
    use CreatedAtFilter;
    use HasTranslations;

    protected $fillable = ['name'];

    public array $translatable = ['name'];

    public function attributes(): BelongsToMany
    {
        return $this->belongsToMany(Attribute::class);

    }
}
