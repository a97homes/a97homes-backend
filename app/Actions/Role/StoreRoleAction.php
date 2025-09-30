<?php

namespace App\Actions\Role;

use Spatie\Permission\Models\Role;

class StoreRoleAction
{
    public function execute(array $data): Role
    {
        $data['guard'] = 'web';

        return Role::create($data);
    }
}
