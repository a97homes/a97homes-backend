<?php

namespace App\Enums;

enum PropertyStatusEnum: string
{
    case PENDING = 'pending';
    case ACTIVE = 'active';
    case BLOCKED = 'blocked';
}
