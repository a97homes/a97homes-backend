<?php

namespace App\Actions\User;

use App\Models\Permission;
use App\Models\User\User;

class AssignPermissionsToUserAction
{
    /**
     * Grant the given permissions to the user on top of the ones already held.
     *
     * @param  array<int, int|string>  $permissionIds
     */
    public function execute(User $user, array $permissionIds): User
    {
        $permissionNames = Permission::query()
            ->whereIn('id', $permissionIds)
            ->pluck('name')
            ->all();

        return $user->givePermissionTo($permissionNames);
    }
}
