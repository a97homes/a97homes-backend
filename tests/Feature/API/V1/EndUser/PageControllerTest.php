<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\Page;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_published_pages_sorted_by_sort_order(): void
    {
        Page::factory()->create(['slug' => 'about-us', 'sort_order' => 1]);
        Page::factory()->create(['slug' => 'privacy', 'sort_order' => 3]);
        Page::factory()->create(['slug' => 'terms', 'sort_order' => 2]);
        Page::factory()->draft()->create(['slug' => 'hidden-draft', 'sort_order' => 0]);

        $response = $this->getJson('/api/V1/pages');

        $response->assertOk();
        $slugs = array_column($response->json('data'), 'slug');
        $this->assertSame(['about-us', 'terms', 'privacy'], $slugs);
    }

    public function test_show_returns_page_by_slug(): void
    {
        Page::factory()->create(['slug' => 'about-us']);

        $this->getJson('/api/V1/pages/about-us')
            ->assertOk()
            ->assertJsonPath('data.slug', 'about-us');
    }

    public function test_show_returns_404_for_unpublished(): void
    {
        Page::factory()->draft()->create(['slug' => 'hidden']);

        $this->getJson('/api/V1/pages/hidden')->assertNotFound();
    }

    public function test_show_returns_404_for_missing_slug(): void
    {
        $this->getJson('/api/V1/pages/does-not-exist')->assertNotFound();
    }
}
