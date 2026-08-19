<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\Compound;
use App\Models\Developer;
use App\Models\Property;
use App\Models\SubArea;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CompoundIndexSortTest extends TestCase
{
    use RefreshDatabase;

    private function makeCompound(int $minPrice, string $createdAt): Compound
    {
        $compound = Compound::factory()->create([
            'developer_id' => Developer::factory(),
            'sub_area_id' => SubArea::factory(),
            'created_at' => $createdAt,
        ]);

        Property::query()->create([
            'compound_id' => $compound->id,
            'address' => 'addr',
            'price' => $minPrice,
        ]);

        return $compound;
    }

    public function test_sort_by_price_ascending_and_descending(): void
    {
        $cheap = $this->makeCompound(minPrice: 100, createdAt: '2026-01-01 00:00:00');
        $pricey = $this->makeCompound(minPrice: 9000, createdAt: '2026-01-01 00:00:00');

        $this->getJson('/api/V1/compounds?sort=price')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $cheap->id)
            ->assertJsonPath('data.data.1.id', $pricey->id);

        $this->getJson('/api/V1/compounds?sort=-price')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $pricey->id)
            ->assertJsonPath('data.data.1.id', $cheap->id);
    }

    public function test_sort_by_created_at_is_deterministic_with_identical_timestamps(): void
    {
        $first = $this->makeCompound(minPrice: 100, createdAt: '2026-01-01 00:00:00');
        $second = $this->makeCompound(minPrice: 100, createdAt: '2026-01-01 00:00:00');

        $this->getJson('/api/V1/compounds?sort=-created_at')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $second->id)
            ->assertJsonPath('data.data.1.id', $first->id);

        $this->getJson('/api/V1/compounds?sort=created_at')
            ->assertOk()
            ->assertJsonPath('data.data.0.id', $first->id)
            ->assertJsonPath('data.data.1.id', $second->id);
    }
}
