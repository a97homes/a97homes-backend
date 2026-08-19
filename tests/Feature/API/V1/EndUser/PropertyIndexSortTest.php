<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\Compound;
use App\Models\Developer;
use App\Models\Property;
use App\Models\SubArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PropertyIndexSortTest extends TestCase
{
    use RefreshDatabase;

    private function makeProperty(string $createdAt): Property
    {
        $property = Property::query()->create([
            'compound_id' => Compound::factory()->create([
                'developer_id' => Developer::factory(),
                'sub_area_id' => SubArea::factory(),
            ])->id,
            'address' => 'addr',
            'price' => 100,
        ]);

        $property->forceFill(['created_at' => $createdAt])->save();

        return $property;
    }

    public function test_sort_by_created_at_is_deterministic_with_identical_timestamps(): void
    {
        $first = $this->makeProperty('2026-01-01 00:00:00');
        $second = $this->makeProperty('2026-01-01 00:00:00');

        $this->getJson('/api/V1/properties?sort=-created_at')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $second->id)
            ->assertJsonPath('data.data.1.id', $first->id);

        $this->getJson('/api/V1/properties?sort=created_at')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $first->id)
            ->assertJsonPath('data.data.1.id', $second->id);
    }
}
