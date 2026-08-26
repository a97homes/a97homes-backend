<?php

namespace Database\Seeders;

use App\Enums\Role\UserRoleEnum;
use App\Models\Role;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class UserRoleSeeder extends Seeder
{
    /**
     * Seeds one account per role.
     *
     * The data entry role is skipped here - DataEntryRoleSeeder owns both its
     * permissions and its single account (mostafa@a97.com).
     */
    // php artisan db:seed --class=UserRoleSeeder
    public function run(): void
    {
        $roles = UserRoleEnum::cases();

        foreach ($roles as $roleEnum) {

            if ($roleEnum === UserRoleEnum::DATA_ENTRY) {
                continue;
            }

            $role = Role::firstOrCreate([
                'name' => $roleEnum->value,
                'guard_name' => Config::get('auth.defaults.guard'),
            ]);

            $user = User::updateOrCreate(
                [
                    'email' => $roleEnum->value.'@'.$roleEnum->value.'.com',
                ],
                [
                    'name' => $roleEnum->value,
                    'password' => 'password',
                ]
            );
            if (! $user->hasRole($roleEnum->value)) {
                $user->assignRole($roleEnum->value);
            }
        }
    }
}
