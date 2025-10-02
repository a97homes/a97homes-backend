<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class Unit extends Model
{
    use CreatedAtFilter;
    use HasTranslations;

    public array $translatable = ['name', 'symbol'];

    protected $fillable = ['name', 'symbol', 'type'];
}
