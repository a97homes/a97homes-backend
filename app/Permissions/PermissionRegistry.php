<?php

namespace App\Permissions;

use ReflectionClass;

class PermissionRegistry
{
    const ADMIN_ROLES_INDEX = 'roles.index';

    const ADMIN_ROLES_STORE = 'roles.store';

    const ADMIN_ROLES_SHOW = 'roles.show';

    const ADMIN_ROLES_UPDATE = 'roles.update';

    const ADMIN_ROLES_DESTROY = 'roles.destroy';

    public static function all(): array
    {
        $reflection = new ReflectionClass(__CLASS__);
        $constants = $reflection->getConstants();

        return array_values($constants);
    }

    public static function exists(string $permission): bool
    {
        return in_array($permission, self::all());
    }
}
