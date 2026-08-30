<?php

namespace Database\Seeders;

use App\Enums\Role\UserRoleEnum;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User\User;
use App\Permissions\PermissionScanner;
use Illuminate\Database\Seeder;
use Illuminate\Support\Collection;
use Spatie\Permission\Guard;
use Spatie\Permission\PermissionRegistrar;

// php artisan db:seed --class=PermissionSeeder
class PermissionSeeder extends Seeder
{
    public function run(): void
    {
        $guardName = Guard::getDefaultName(User::class);
        $discovered = PermissionScanner::all();

        $created = $this->createMissingPermissions($discovered, $guardName);
        [$deleted, $preserved] = $this->pruneStalePermissions($discovered, $guardName);

        $this->grantEveryPermissionToAdmin($guardName);

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        $this->report($discovered, $created, $deleted, $preserved);
    }

    /**
     * Insert the discovered permissions that are not stored yet.
     *
     * @param  array<int, string>  $discovered
     * @return array<int, string>
     */
    private function createMissingPermissions(array $discovered, string $guardName): array
    {
        $existing = Permission::query()
            ->where('guard_name', $guardName)
            ->pluck('name')
            ->all();

        $missing = array_values(array_diff($discovered, $existing));

        foreach ($missing as $name) {
            Permission::query()->create([
                'name' => $name,
                'guard_name' => $guardName,
            ]);
        }

        return $missing;
    }

    /**
     * Drop permissions the code no longer guards with, keeping the ones still in use.
     *
     * @param  array<int, string>  $discovered
     * @return array{0: array<int, string>, 1: array<int, string>}
     */
    private function pruneStalePermissions(array $discovered, string $guardName): array
    {
        $stale = Permission::query()
            ->where('guard_name', $guardName)
            ->whereNotIn('name', $discovered)
            ->withCount('users')
            ->with('roles:id,name')
            ->get();

        $deleted = [];
        $preserved = [];

        foreach ($stale as $permission) {
            if ($this->isStillInUse($permission)) {
                $preserved[] = $permission->name;

                continue;
            }

            $permission->delete();
            $deleted[] = $permission->name;
        }

        return [$deleted, $preserved];
    }

    /**
     * A permission is in use when a user holds it directly or a non admin role grants it.
     *
     * The admin role is excluded because it is granted every permission below,
     * which would otherwise make every stale permission look used.
     */
    private function isStillInUse(Permission $permission): bool
    {
        if ($permission->users_count > 0) {
            return true;
        }

        return $permission->roles
            ->pluck('name')
            ->contains(fn (string $roleName): bool => $roleName !== UserRoleEnum::ADMIN->value);
    }

    /**
     * The admin role always holds every permission.
     */
    private function grantEveryPermissionToAdmin(string $guardName): void
    {
        $admin = Role::firstOrCreate([
            'name' => UserRoleEnum::ADMIN->value,
            'guard_name' => $guardName,
        ]);

        $permissionIds = Permission::query()
            ->where('guard_name', $guardName)
            ->pluck('id')
            ->all();

        $admin->permissions()->syncWithoutDetaching($permissionIds);
    }

    /**
     * @param  array<int, string>  $discovered
     * @param  array<int, string>  $created
     * @param  array<int, string>  $deleted
     * @param  array<int, string>  $preserved
     */
    private function report(array $discovered, array $created, array $deleted, array $preserved): void
    {
        $this->command?->info(sprintf(
            'Permissions synced: %d discovered, %d created, %d deleted, %d preserved.',
            count($discovered),
            count($created),
            count($deleted),
            count($preserved),
        ));

        $this->listNames('Created', $created);
        $this->listNames('Deleted (unused)', $deleted);
        $this->listNames('Preserved (still assigned)', $preserved);
    }

    /**
     * @param  array<int, string>  $names
     */
    private function listNames(string $label, array $names): void
    {
        if ($names === [] || $this->command === null) {
            return;
        }

        $this->command->line("  {$label}: ".(new Collection($names))->implode(', '));
    }
}
