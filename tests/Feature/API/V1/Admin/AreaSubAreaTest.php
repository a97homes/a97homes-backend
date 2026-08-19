<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Models\Area;
use App\Models\Country;
use App\Models\Role;
use App\Models\SubArea;
use App\Models\User\User;
use Database\Seeders\CountrySeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AreaSubAreaTest extends TestCase
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

    public function test_country_seeder_leaves_egypt_as_the_only_country(): void
    {
        Country::factory()->create(['code' => 'SA']);
        Country::factory()->create(['code' => 'AE']);

        $this->seed(CountrySeeder::class);

        $this->assertSame(1, Country::query()->count());
        $this->assertSame('Egypt', Country::query()->first()->getTranslation('name', 'en'));
    }

    public function test_country_area_and_sub_area_relationships(): void
    {
        $country = Country::factory()->create();
        $area = Area::factory()->create(['country_id' => $country->id]);
        $subArea = SubArea::factory()->create(['area_id' => $area->id]);

        $this->assertTrue($country->areas->contains($area));
        $this->assertSame($country->id, $area->country->id);
        $this->assertTrue($area->subAreas->contains($subArea));
        $this->assertSame($area->id, $subArea->area->id);
    }

    public function test_areas_index_returns_the_new_terminology_and_paginates(): void
    {
        $this->actingAsAdmin();
        Area::factory()->count(3)->create();

        $response = $this->getJson('/api/admin/V1/areas?per_page=2')
            ->assertOk()
            ->assertJsonCount(2, 'data.data')
            ->assertJsonStructure([
                'data' => [
                    'data' => [['id', 'name', 'about', 'country', 'sub_areas_count', 'created_at']],
                ],
            ]);

        $this->assertSame(3, $response->json('data.meta.total'));
    }

    public function test_areas_index_filters_by_country_and_searches_by_name(): void
    {
        $this->actingAsAdmin();
        $country = Country::factory()->create();
        $match = Area::factory()->create([
            'country_id' => $country->id,
            'name' => ['en' => 'North Coast', 'ar' => 'الساحل الشمالي'],
        ]);
        Area::factory()->create(['name' => ['en' => 'Red Sea', 'ar' => 'البحر الأحمر']]);

        $this->getJson("/api/admin/V1/areas?filter[country_id]={$country->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $match->id);

        $this->getJson('/api/admin/V1/areas?filter[search]=North')
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $match->id);
    }

    public function test_areas_index_sorts_by_whitelisted_column_only(): void
    {
        $this->actingAsAdmin();
        $first = Area::factory()->create();
        $second = Area::factory()->create();

        $this->getJson('/api/admin/V1/areas?sort=id')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $first->id);

        $this->getJson('/api/admin/V1/areas?sort=-id')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $second->id);

        $this->getJson('/api/admin/V1/areas?sort=country_id')
            ->assertStatus(400);
    }

    public function test_area_can_be_created_with_translatable_about(): void
    {
        $this->actingAsAdmin();
        $country = Country::factory()->create();

        $this->postJson('/api/admin/V1/areas', [
            'name' => ['en' => 'New Cairo', 'ar' => 'القاهرة الجديدة'],
            'about' => ['en' => 'An area', 'ar' => 'منطقة'],
            'country_id' => $country->id,
        ])
            ->assertOk()
            ->assertJsonPath('data.about.en', 'An area')
            ->assertJsonPath('data.about.ar', 'منطقة');

        $this->assertDatabaseCount('areas', 1);
    }

    public function test_area_store_validates_country_id(): void
    {
        $this->actingAsAdmin();

        $this->postJson('/api/admin/V1/areas', [
            'name' => ['en' => 'Nowhere', 'ar' => 'لا مكان'],
            'country_id' => 999999,
        ])
            ->assertStatus(422)
            ->assertJsonStructure(['message' => ['country_id']]);
    }

    public function test_sub_areas_index_filters_by_area_and_country(): void
    {
        $this->actingAsAdmin();
        $country = Country::factory()->create();
        $area = Area::factory()->create(['country_id' => $country->id]);
        $match = SubArea::factory()->create(['area_id' => $area->id]);
        SubArea::factory()->create();

        $this->getJson("/api/admin/V1/sub-areas?filter[area_id]={$area->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $match->id);

        $this->getJson("/api/admin/V1/sub-areas?filter[country_id]={$country->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $match->id);
    }

    public function test_sub_areas_index_exposes_the_parent_area_and_paginates(): void
    {
        $this->actingAsAdmin();
        $area = Area::factory()->create();
        SubArea::factory()->count(3)->create(['area_id' => $area->id]);

        $response = $this->getJson('/api/admin/V1/sub-areas?per_page=2&sort=id')
            ->assertOk()
            ->assertJsonCount(2, 'data.data')
            ->assertJsonPath('data.data.0.area.id', $area->id);

        $this->assertSame(3, $response->json('data.meta.total'));
    }

    public function test_sub_area_store_requires_an_existing_area(): void
    {
        $this->actingAsAdmin();

        $this->postJson('/api/admin/V1/sub-areas', [
            'name' => ['en' => 'Nowhere', 'ar' => 'لا مكان'],
            'area_id' => 999999,
        ])
            ->assertStatus(422)
            ->assertJsonStructure(['message' => ['area_id']]);
    }

    public function test_public_nested_location_endpoints_use_the_new_terminology(): void
    {
        $country = Country::factory()->create();
        $area = Area::factory()->create(['country_id' => $country->id]);
        $subArea = SubArea::factory()->create(['area_id' => $area->id]);

        $this->getJson("/api/V1/countries/{$country->id}/areas")
            ->assertOk()
            ->assertJsonPath('data.0.id', $area->id);

        $this->getJson("/api/V1/areas/{$area->id}/sub-areas")
            ->assertOk()
            ->assertJsonPath('data.0.id', $subArea->id);
    }
}
