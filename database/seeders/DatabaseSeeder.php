<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();
        $this->call([UserRoleSeeder::class]);
        $this->call([PermissionSeeder::class]);
        $this->call(UnitSeeder::class);
        $this->call([AttributesSeeder::class]);
        $this->call([AttributeOptionSeeder::class]);
        $this->call([CountrySeeder::class]);
        $this->call([StateSeeder::class]);
        $this->call([CitySeeder::class]);
        $this->call([PropertyTypeSeeder::class]);
        $this->call([DeveloperSeeder::class]);
        $this->call([PropertySeeder::class]);
    }
}
