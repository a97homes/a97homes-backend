<?php

namespace App\Enums\User;

enum UserStatusEnum: string
{
    case ACTIVE = 'active';

    case PENDING = 'pending';
}
