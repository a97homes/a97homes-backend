<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Propaganistas\LaravelPhone\Casts\E164PhoneNumberCast;

class Contact extends Model
{
    protected $fillable = [
        'name',
        'email',
        'message',
        'phone',
    ];

    protected function casts(): array
    {
        return [
            'phone' => E164PhoneNumberCast::class,
        ];
    }
}
