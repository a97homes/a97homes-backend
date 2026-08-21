<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\SavedSearchTypeEnum;
use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SavedSearch extends Model
{
    /** @use HasFactory<\Database\Factories\SavedSearchFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'type',
        'criteria',
        'notify_by_email',
        'last_checked_at',
    ];

    protected function casts(): array
    {
        return [
            'type' => SavedSearchTypeEnum::class,
            'criteria' => 'array',
            'notify_by_email' => 'boolean',
            'last_checked_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
