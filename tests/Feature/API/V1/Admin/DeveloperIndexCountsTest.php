<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Models\City;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Property;
use App\Models\Role;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeveloperIndexCountsTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        Role::firstOrCreate([
            'name' => UserRoleEnum::ADMIN->value,
            'guard_name' => Config::get('auth.defaults.guard'),
        ]);

        $admin = User::factory()->create();
        $admin->assignRole(UserRoleEnum::ADMIN->value);
        Sanctum::actingAs($admin);

        return $admin;
    }

    private function seedDeveloperWith(int $compounds, int $unitsPerCompound, int $distinctCities): Developer
    {
        $developer = Developer::factory()->create();
        $cities = City::factory()->count(max($distinctCities, 1))->create();

        for ($i = 0; $i < $compounds; $i++) {
            $compound = Compound::factory()->create([
                'developer_id' => $developer->id,
                'city_id' => $cities[$i % $cities->count()]->id,
            ]);

            for ($u = 0; $u < $unitsPerCompound; $u++) {
                Property::query()->create([
                    'compound_id' => $compound->id,
                    'address' => 'Test address',
                ]);
            }
        }

        return $developer;
    }

    public function test_index_returns_compounds_units_and_areas_counts(): void
    {
        $this->actingAsAdmin();
        $developer = $this->seedDeveloperWith(compounds: 3, unitsPerCompound: 2, distinctCities: 2);

        $this->getJson('/api/admin/V1/developers')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $developer->id)
            ->assertJsonPath('data.data.0.compounds_count', 3)
            ->assertJsonPath('data.data.0.units_count', 6)
            ->assertJsonPath('data.data.0.areas_count', 2);
    }

    public function test_index_filters_by_is_active(): void
    {
        $this->actingAsAdmin();
        $active = Developer::factory()->create();
        Developer::factory()->inactive()->create();

        $this->getJson('/api/admin/V1/developers?filter[is_active]=1')
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $active->id);
    }

    public function test_index_sorts_by_units_count_desc(): void
    {
        $this->actingAsAdmin();
        $few = $this->seedDeveloperWith(compounds: 1, unitsPerCompound: 1, distinctCities: 1);
        $many = $this->seedDeveloperWith(compounds: 1, unitsPerCompound: 5, distinctCities: 1);

        $this->getJson('/api/admin/V1/developers?sort=-units_count')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $many->id)
            ->assertJsonPath('data.data.1.id', $few->id);
    }
}
