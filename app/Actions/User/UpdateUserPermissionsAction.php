<?php

namespace App\Actions\User;

use App\Models\Permission;
use App\Models\User\User;

class UpdateUserPermissionsAction
{
    /**
     * Replace the user's direct permissions with the given ones.
     *
     * Permissions inherited from the user's roles are untouched.
     *
     * @param  array<int, int|string>  $permissionIds
     */
    public function execute(User $user, array $permissionIds): User
    {
        $permissionNames = Permission::query()
            ->whereIn('id', $permissionIds)
            ->pluck('name')
            ->all();

        $user->syncPermissions($permissionNames);

        return $user;
    }
}
