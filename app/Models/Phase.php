<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\CompletionStatusEnum;
use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Phase extends Model
{
    use CreatedAtFilter;

    /** @use HasFactory<\Database\Factories\PhaseFactory> */
    use HasFactory;

    use HasTranslations;

    public array $translatable = ['name', 'description'];

    protected $fillable = [
        'compound_id',
        'name',
        'description',
        'delivery_date',
        'completion_status',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'completion_status' => CompletionStatusEnum::class,
            'delivery_date' => 'date',
            'sort_order' => 'integer',
        ];
    }

    public function compound(): BelongsTo
    {
        return $this->belongsTo(Compound::class);
    }
}
