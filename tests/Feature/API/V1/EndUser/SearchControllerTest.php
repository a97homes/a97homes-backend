<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\Compound;
use App\Models\Developer;
use App\Models\SubArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_suggest_returns_empty_payload_for_short_query(): void
    {
        $this->getJson('/api/V1/search/suggest?q=a')
            ->assertOk()
            ->assertJsonPath('data.compounds', [])
            ->assertJsonPath('data.properties', [])
            ->assertJsonPath('data.developers', [])
            ->assertJsonPath('data.sub_areas', []);
    }

    public function test_suggest_returns_results_grouped_by_type(): void
    {
        $subArea = SubArea::factory()->create(['name' => ['en' => 'Alamein SubArea', 'ar' => 'مدينة العلمين']]);
        $developer = Developer::factory()->create(['name' => 'Alamein Developments']);
        Compound::factory()->create([
            'name' => 'Alamein Heights',
            'sub_area_id' => $subArea->id,
            'developer_id' => $developer->id,
        ]);

        $response = $this->getJson('/api/V1/search/suggest?q=Alamein&limit=3');

        $response->assertOk()
            ->assertJsonPath('data.query', 'Alamein')
            ->assertJsonCount(1, 'data.compounds')
            ->assertJsonCount(1, 'data.developers')
            ->assertJsonCount(1, 'data.sub_areas')
            ->assertJsonPath('data.compounds.0.type', 'compound')
            ->assertJsonPath('data.developers.0.type', 'developer')
            ->assertJsonPath('data.sub_areas.0.type', 'sub_area');
    }

    public function test_suggest_limit_is_capped_to_max(): void
    {
        SubArea::factory()->count(12)->create([
            'name' => fn () => ['en' => 'SameSubAreaName '.fake()->unique()->word(), 'ar' => 'مدينة'],
        ]);

        $response = $this->getJson('/api/V1/search/suggest?q=SameSubAreaName&limit=50');

        $response->assertOk()
            ->assertJsonCount(10, 'data.sub_areas');
    }

    public function test_search_returns_paginated_results_for_valid_type(): void
    {
        Developer::factory()->count(15)->create(['name' => fake()->unique()->company().' TestBrand']);

        $this->getJson('/api/V1/search?q=TestBrand&type=developer&per_page=5')
            ->assertOk()
            ->assertJsonPath('data.meta.total', 15)
            ->assertJsonPath('data.meta.per_page', 5);
    }

    public function test_search_rejects_invalid_type(): void
    {
        $this->getJson('/api/V1/search?q=foo&type=bogus')
            ->assertUnprocessable();
    }

    public function test_search_rejects_short_query(): void
    {
        $this->getJson('/api/V1/search?q=a&type=compound')
            ->assertUnprocessable();
    }
}
