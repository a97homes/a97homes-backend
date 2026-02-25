<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Developer extends Model
{
    use CreatedAtFilter;

    protected $fillable = ['name', 'about'];

    public function projects(): HasMany
    {
        return $this->hasMany(Project::class);
    }
}
