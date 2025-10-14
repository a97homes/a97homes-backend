<?php

namespace App\Actions\User;

use App\Models\User\User;

class AssignRolesToUserAction
{
    
    public function execute(User $user, array $data): User
    {
        return $user->assignRole($data);
    }
}
