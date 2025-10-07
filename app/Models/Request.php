<?php

namespace App\Models;

use App\Enums\UserRequestStatusEnum;
use Illuminate\Foundation\Auth\User;
use Illuminate\Database\Eloquent\Model;

class Request extends Model
{
       protected $fillable = [
        'user_id',
        'status',
    ];

    protected $casts = [
        'status' => UserRequestStatusEnum::class,
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

}
