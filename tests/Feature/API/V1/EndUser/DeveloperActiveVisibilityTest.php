<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\Compound;
use App\Models\Developer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeveloperActiveVisibilityTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_excludes_inactive_developers(): void
    {
        $active = Developer::factory()->create();
        $inactive = Developer::factory()->inactive()->create();

        $response = $this->getJson('/api/V1/developers')->assertOk();

        $ids = array_column($response->json('data.data'), 'id');
        $this->assertContains($active->id, $ids);
        $this->assertNotContains($inactive->id, $ids);
    }

    public function test_show_returns_404_for_inactive_developer(): void
    {
        $inactive = Developer::factory()->inactive()->create();

        $this->getJson("/api/V1/developers/{$inactive->id}")->assertNotFound();
    }

    public function test_show_returns_active_developer(): void
    {
        $active = Developer::factory()->create();

        $this->getJson("/api/V1/developers/{$active->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $active->id);
    }

    public function test_dropdown_excludes_inactive_developers(): void
    {
        $active = Developer::factory()->create();
        $inactive = Developer::factory()->inactive()->create();

        $response = $this->getJson('/api/V1/dropdowns/developers')->assertOk();

        $ids = array_column($response->json('data'), 'id');
        $this->assertContains($active->id, $ids);
        $this->assertNotContains($inactive->id, $ids);
    }

    public function test_search_excludes_inactive_developers(): void
    {
        $active = Developer::factory()->create(['name' => 'Skyline Group']);
        Developer::factory()->inactive()->create(['name' => 'Skyline Towers']);

        $response = $this->getJson('/api/V1/search?type=developer&q=Skyline')->assertOk();

        $ids = array_column($response->json('data.data'), 'id');
        $this->assertSame([$active->id], $ids);
    }

    public function test_compound_hides_inactive_developer(): void
    {
        $inactive = Developer::factory()->inactive()->create();
        $compound = Compound::factory()->create(['developer_id' => $inactive->id]);

        $this->getJson("/api/V1/compounds/{$compound->id}")
            ->assertOk()
            ->assertJsonMissingPath('data.developer');
    }

    public function test_compound_exposes_active_developer(): void
    {
        $active = Developer::factory()->create();
        $compound = Compound::factory()->create(['developer_id' => $active->id]);

        $this->getJson("/api/V1/compounds/{$compound->id}")
            ->assertOk()
            ->assertJsonPath('data.developer.id', $active->id);
    }
}
