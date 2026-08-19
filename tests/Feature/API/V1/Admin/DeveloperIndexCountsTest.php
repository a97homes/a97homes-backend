<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Role;
use App\Models\SubArea;
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

    private function seedDeveloperWith(int $compounds, int $unitsPerCompound, int $distinctSubAreas): Developer
    {
        $developer = Developer::factory()->create();
        $subAreas = SubArea::factory()->count(max($distinctSubAreas, 1))->create();

        for ($i = 0; $i < $compounds; $i++) {
            $compound = Compound::factory()->create([
                'developer_id' => $developer->id,
                'sub_area_id' => $subAreas[$i % $subAreas->count()]->id,
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

    public function test_index_returns_compounds_units_and_sub_areas_counts(): void
    {
        $this->actingAsAdmin();
        $developer = $this->seedDeveloperWith(compounds: 3, unitsPerCompound: 2, distinctSubAreas: 2);

        $this->getJson('/api/admin/V1/developers')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $developer->id)
            ->assertJsonPath('data.data.0.compounds_count', 3)
            ->assertJsonPath('data.data.0.units_count', 6)
            ->assertJsonPath('data.data.0.sub_areas_count', 2);
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

    public function test_index_returns_the_developer_sub_areas(): void
    {
        $this->actingAsAdmin();
        $developer = $this->seedDeveloperWith(compounds: 3, unitsPerCompound: 1, distinctSubAreas: 2);

        $response = $this->getJson('/api/admin/V1/developers')->assertOk();

        $subAreas = $response->json('data.data.0.sub_areas');

        $this->assertCount(2, $subAreas);
        $this->assertEqualsCanonicalizing(
            $developer->compounds()->pluck('sub_area_id')->unique()->values()->all(),
            array_column($subAreas, 'id'),
        );

        foreach ($subAreas as $subArea) {
            $this->assertArrayHasKey('area', $subArea);
            $this->assertArrayHasKey('compounds_count', $subArea);
            $this->assertArrayHasKey('units_count', $subArea);
        }

        $this->assertEqualsCanonicalizing([1, 2], array_column($subAreas, 'compounds_count'));
        $this->assertEqualsCanonicalizing([1, 2], array_column($subAreas, 'units_count'));
    }

    public function test_index_filters_by_sub_area_id(): void
    {
        $this->actingAsAdmin();
        $developer = $this->seedDeveloperWith(compounds: 1, unitsPerCompound: 1, distinctSubAreas: 1);
        $this->seedDeveloperWith(compounds: 1, unitsPerCompound: 1, distinctSubAreas: 1);

        $subAreaId = $developer->compounds()->value('sub_area_id');

        $this->getJson("/api/admin/V1/developers?filter[sub_area_id]={$subAreaId}")
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $developer->id);
    }

    public function test_index_filters_by_area_property_type_sale_type_and_completion_status(): void
    {
        $this->actingAsAdmin();
        $match = $this->seedDeveloperWith(compounds: 1, unitsPerCompound: 1, distinctSubAreas: 1);
        $other = $this->seedDeveloperWith(compounds: 1, unitsPerCompound: 1, distinctSubAreas: 1);

        $matchingType = PropertyType::query()->create(['name' => ['en' => 'Matching Type', 'ar' => 'Matching Type']]);
        $otherType = PropertyType::query()->create(['name' => ['en' => 'Other Type', 'ar' => 'Other Type']]);

        $compound = $match->compounds()->firstOrFail();
        $property = $match->properties()->firstOrFail();
        $property->forceFill([
            'property_type_id' => $matchingType->id,
            'sale_type' => 'resale',
        ])->save();
        $compound->forceFill(['completion_status' => 'completed'])->save();

        $other->properties()->firstOrFail()->forceFill([
            'property_type_id' => $otherType->id,
            'sale_type' => 'developer',
        ])->save();
        $other->compounds()->firstOrFail()->forceFill(['completion_status' => 'under_construction'])->save();

        $areaId = $compound->subArea->area_id;

        $this->getJson("/api/admin/V1/developers?filter[area_id]={$areaId}&filter[property_type_id]={$matchingType->id}&filter[sale_type]=resale&filter[completion_status]=completed")
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $match->id);
    }

    public function test_index_filters_by_min_compounds_and_min_units(): void
    {
        $this->actingAsAdmin();
        $this->seedDeveloperWith(compounds: 1, unitsPerCompound: 1, distinctSubAreas: 1);
        $big = $this->seedDeveloperWith(compounds: 3, unitsPerCompound: 4, distinctSubAreas: 2);

        $this->getJson('/api/admin/V1/developers?filter[min_compounds]=2')
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $big->id);

        $this->getJson('/api/admin/V1/developers?filter[min_units]=5')
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $big->id);
    }

    public function test_index_sorts_by_units_count_desc(): void
    {
        $this->actingAsAdmin();
        $few = $this->seedDeveloperWith(compounds: 1, unitsPerCompound: 1, distinctSubAreas: 1);
        $many = $this->seedDeveloperWith(compounds: 1, unitsPerCompound: 5, distinctSubAreas: 1);

        $this->getJson('/api/admin/V1/developers?sort=-units_count')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $many->id)
            ->assertJsonPath('data.data.1.id', $few->id);
    }
}
