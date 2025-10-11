<?php

namespace Database\Seeders;

use App\Enums\Role\UserRoleEnum;
use App\Models\Role;
use App\Models\User\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Config;

class UserRoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = UserRoleEnum::cases();

        foreach ($roles as $roleEnum) {

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
