<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'phone',
        'city_id',
    ];

    protected function casts(): array
    {
        return [
            'phone' => E164PhoneNumberCast::class,
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
