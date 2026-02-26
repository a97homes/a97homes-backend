<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use CreatedAtFilter;

    protected $fillable = ['name', 'developer_id'];

    public function developer(): BelongsTo
    {
        return $this->belongsTo(Developer::class);
    }

    public function phases(): HasMany
    {
        return $this->hasMany(Phase::class);
    }
}
