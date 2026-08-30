<?php

namespace Database\Seeders;

use App\Enums\Role\UserRoleEnum;
use App\Models\Role;
use App\Models\User\User;
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
        'admin.developers.index',
        'admin.developers.store',
        'admin.developers.show',
        'admin.developers.update',
        'admin.developers.destroy',
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
        $user->syncRoles([UserRoleEnum::DATA_ENTRY->value]);
    }
}
