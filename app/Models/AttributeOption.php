<?php

namespace App\Models;

use App\Filters\CreatedAtFilter;
use App\Traits\HasArabicSearch;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Translatable\HasTranslations;

class AttributeOption extends Model
{
    use CreatedAtFilter;
    use HasArabicSearch;
    use HasTranslations;

    public array $translatable = ['value'];

    protected $fillable = ['attribute_id', 'value', 'sort_order', 'is_active'];

    public function attribute(): BelongsTo
    {
        return $this->belongsTo(Attribute::class);
    }

    public function properties(): BelongsToMany
    {
        return $this->belongsToMany(Property::class, 'attribute_property_option')
            ->withPivot('attribute_id')
            ->withTimestamps();
    }

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }
}
