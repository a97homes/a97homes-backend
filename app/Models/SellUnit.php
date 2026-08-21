<?php

namespace App\Models;

use App\Enums\SellUnitStatusEnum;
use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class SellUnit extends Model
{
    use CreatedAtFilter, HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'sub_area_id',
        'property_type_id',
        'compound_id',
        'notes',
        'status',
    ];

    protected $casts = [
        'status' => SellUnitStatusEnum::class,
        'phone' => E164PhoneNumberCast::class,
    ];

    public function subArea(): BelongsTo
    {
        return $this->belongsTo(SubArea::class);
    }

    public function propertyType(): BelongsTo
    {
        return $this->belongsTo(PropertyType::class);
    }

    public function compound(): BelongsTo
    {
        return $this->belongsTo(Compound::class);
    }
}
