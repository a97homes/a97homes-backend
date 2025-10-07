<?php

namespace App\Enums\Role;

enum UserRoleEnum: string
{
    case ADMIN = 'admin';
    case OWNER ='owner';
}
