<?php

namespace App\Actions\Role;

use App\Models\Permission;
use App\Models\Role;

class UpdateRolePermissionsAction
{
    /**
     * Replace the role's permissions with the given ones.
     *
     * @param  array<int, int|string>  $permissionIds
     */
    public function execute(Role $role, array $permissionIds): Role
    {
        $permissionNames = Permission::query()
            ->whereIn('id', $permissionIds)
            ->pluck('name')
            ->all();

        $role->syncPermissions($permissionNames);

        return $role;
    }
}
