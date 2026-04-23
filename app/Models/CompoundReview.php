<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CompoundReview extends Model
{
    /** @use HasFactory<\Database\Factories\CompoundReviewFactory> */
    use HasFactory;

    protected $fillable = [
        'compound_id',
        'user_id',
        'title',
        'comment',
        'overall_rating',
        'location_rating',
        'amenities_rating',
        'value_for_money_rating',
        'developer_reputation_rating',
    ];

    protected function casts(): array
    {
        return [
            'overall_rating' => 'integer',
            'location_rating' => 'integer',
            'amenities_rating' => 'integer',
            'value_for_money_rating' => 'integer',
            'developer_reputation_rating' => 'integer',
        ];
    }

    public function compound(): BelongsTo
    {
        return $this->belongsTo(Compound::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
