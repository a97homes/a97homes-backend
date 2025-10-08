<?php

namespace App\Actions\Register;

use App\Models\User\User;

class RegisterAction
{
    public function execute(array $data): User
    {
        return User::create($data);
    }
}
