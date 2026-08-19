<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\Compound;
use App\Models\Developer;
use App\Models\Property;
use App\Models\SubArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MapEndpointsTest extends TestCase
{
    use RefreshDatabase;

    private function makeProperty(array $attributes = []): Property
    {
        $compound = Compound::factory()->create([
            'developer_id' => Developer::factory(),
            'sub_area_id' => SubArea::factory(),
        ]);

        return Property::query()->create(array_merge([
            'compound_id' => $compound->id,
            'address' => 'addr',
            'price' => 1000,
            'latitude' => 30.1234567,
            'longitude' => 31.1234567,
        ], $attributes));
    }

    public function test_compounds_map_is_not_paginated_and_uses_sub_area_coordinates(): void
    {
        $subArea = SubArea::factory()->create(['latitude' => 30.05, 'longitude' => 31.23]);
        $compound = Compound::factory()->create([
            'developer_id' => Developer::factory(),
            'sub_area_id' => $subArea->id,
        ]);

        $response = $this->getJson('/api/V1/compounds/map')->assertOk();

        $response->assertJsonMissingPath('data.meta');
        $response->assertJsonPath('data.0.id', $compound->id);
        $response->assertJsonPath('data.0.latitude', 30.05);
        $response->assertJsonPath('data.0.longitude', 31.23);
    }

    public function test_compounds_map_excludes_compounds_without_sub_area_coordinates(): void
    {
        $subArea = SubArea::factory()->create(['latitude' => null, 'longitude' => null]);
        Compound::factory()->create([
            'developer_id' => Developer::factory(),
            'sub_area_id' => $subArea->id,
        ]);

        $this->getJson('/api/V1/compounds/map')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_properties_map_is_not_paginated_and_returns_coordinates(): void
    {
        $property = $this->makeProperty();

        $response = $this->getJson('/api/V1/properties/map')->assertOk();

        $response->assertJsonMissingPath('data.meta');
        $response->assertJsonPath('data.0.id', $property->id);
        $response->assertJsonPath('data.0.latitude', 30.1234567);
        $response->assertJsonPath('data.0.longitude', 31.1234567);
    }

    public function test_properties_map_excludes_properties_without_coordinates(): void
    {
        $this->makeProperty(['latitude' => null, 'longitude' => null]);

        $this->getJson('/api/V1/properties/map')
            ->assertOk()
            ->assertJsonCount(0, 'data');
    }

    public function test_properties_map_respects_filters(): void
    {
        $cheap = $this->makeProperty(['price' => 500]);
        $this->makeProperty(['price' => 50000]);

        $this->getJson('/api/V1/properties/map?price_max=1000')
            ->assertOk()
            ->assertJsonCount(1, 'data')
            ->assertJsonPath('data.0.id', $cheap->id);
    }
}
