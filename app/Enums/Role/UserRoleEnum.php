<?php

namespace App\Enums\Role;

enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case USER = 'user';

    case DATA_ENTRY = 'data_entry';
}
