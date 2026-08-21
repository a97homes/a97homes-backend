<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\Compound;
use App\Models\Phase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PhaseControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_by_compound_returns_phases_sorted(): void
    {
        $compound = Compound::factory()->create();

        Phase::factory()->create(['compound_id' => $compound->id, 'sort_order' => 3]);
        Phase::factory()->create(['compound_id' => $compound->id, 'sort_order' => 1]);
        Phase::factory()->create(['compound_id' => $compound->id, 'sort_order' => 2]);

        Phase::factory()->create(['compound_id' => Compound::factory()->create()->id]);

        $response = $this->getJson("/api/V1/compounds/{$compound->id}/phases");

        $response->assertOk()
            ->assertJsonCount(3, 'data');

        $orders = array_column($response->json('data'), 'sort_order');
        $this->assertSame([1, 2, 3], $orders);
    }

    public function test_by_compound_returns_empty_when_no_phases(): void
    {
        $compound = Compound::factory()->create();

        $this->getJson("/api/V1/compounds/{$compound->id}/phases")
            ->assertOk()
            ->assertJsonPath('data', []);
    }
}
