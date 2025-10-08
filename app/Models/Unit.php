<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Unit extends Model
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasTranslations;

    public array $translatable = ['name', 'symbol'];

    protected $fillable = ['name', 'symbol', 'type'];
}
