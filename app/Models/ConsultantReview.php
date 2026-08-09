<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsultantReview extends Model
{
    use HasFactory;

    protected $fillable = [
        'consultant_id',
        'user_id',
        'title',
        'comment',
        'overall_rating',
        'local_knowledge_rating',
        'process_expertise_rating',
        'response_speed_rating',
        'negotiation_skills_rating',
    ];

    protected function casts(): array
    {
        return [
            'overall_rating' => 'integer',
            'local_knowledge_rating' => 'integer',
            'process_expertise_rating' => 'integer',
            'response_speed_rating' => 'integer',
            'negotiation_skills_rating' => 'integer',
        ];
    }

    public function consultant(): BelongsTo
    {
        return $this->belongsTo(Consultant::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User\User::class);
    }
}
