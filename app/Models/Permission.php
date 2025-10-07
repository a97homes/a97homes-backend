<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Model;
use Spatie\Permission\Models\Permission as ModelsPermission;
use Spatie\Translatable\HasTranslations;

class Permission extends ModelsPermission
{
    use CreatedAtFilter;

    protected $fillable = ['name'];
}
