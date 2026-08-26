<?php

namespace Database\Seeders;

use App\Enums\Role\UserRoleEnum;
use App\Models\Role;
use App\Models\User;
use App\Permissions\PermissionRegistry;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;
use Spatie\Permission\Models\Permission;

class DataEntryRoleSeeder extends Seeder
{
    /**
     * Permissions of the developer module granted to the data entry role.
     *
     * @var array<int, string>
     */
    private const DEVELOPER_MODULE_PERMISSIONS = [
        PermissionRegistry::ADMIN_DEVELOPERS_INDEX,
        PermissionRegistry::ADMIN_DEVELOPERS_STORE,
        PermissionRegistry::ADMIN_DEVELOPERS_SHOW,
        PermissionRegistry::ADMIN_DEVELOPERS_UPDATE,
        PermissionRegistry::ADMIN_DEVELOPERS_DESTROY,
    ];

    // php artisan db:seed --class=DataEntryRoleSeeder
    public function run(): void
    {
        $guardName = Config::get('auth.defaults.guard');

        $role = Role::firstOrCreate([
            'name' => UserRoleEnum::DATA_ENTRY->value,
            'guard_name' => $guardName,
        ]);

        foreach (self::DEVELOPER_MODULE_PERMISSIONS as $permissionName) {

            $permission = Permission::firstOrCreate([
                'name' => $permissionName,
                'guard_name' => $guardName,
            ]);

            $role->givePermissionTo($permission);
        }

        $user = User::updateOrCreate(
            [
                'email' => 'mostafa@a97.com',
            ],
            [
                'name' => 'Mostafa',
                'password' => 'password',
            ]
        );
        if (! $user->hasRole(UserRoleEnum::DATA_ENTRY->value)) {
            $user->assignRole(UserRoleEnum::DATA_ENTRY->value);
        }
    }
}
