<?php

namespace App\Actions\User;

use App\Models\Role;
use App\Models\User\User;

class AssignRolesToUserAction
{
    public function execute(User $user, array $roleIds): User
    {
        $roleNames = Role::whereIn('id', $roleIds)->pluck('name')->toArray();

        return $user->assignRole($roleNames);
    }
}
