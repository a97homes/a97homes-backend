<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class AttributeOption extends Model
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasTranslations;

    public array $translatable = ['value'];

    protected $fillable = ['attribute_id', 'value', 'sort_order'];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }
}
