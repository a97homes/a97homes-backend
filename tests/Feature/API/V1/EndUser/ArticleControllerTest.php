<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Enums\ArticleTypeEnum;
use App\Models\Article;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ArticleControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_index_returns_only_published_articles(): void
    {
        Article::factory()->count(2)->create();
        Article::factory()->draft()->count(3)->create();

        $response = $this->getJson('/api/V1/articles');

        $response->assertOk()
            ->assertJsonPath('data.meta.total', 2);
    }

    public function test_index_filters_by_type(): void
    {
        Article::factory()->count(2)->create();
        Article::factory()->media()->count(4)->create();
        Article::factory()->news()->count(1)->create();

        $this->getJson('/api/V1/articles?filter[type]=media')
            ->assertOk()
            ->assertJsonPath('data.meta.total', 4);
    }

    public function test_index_filters_featured(): void
    {
        Article::factory()->count(3)->create();
        Article::factory()->featured()->count(2)->create();

        $this->getJson('/api/V1/articles?filter[is_featured]=1')
            ->assertOk()
            ->assertJsonPath('data.meta.total', 2);
    }

    public function test_featured_endpoint_limited_to_six(): void
    {
        Article::factory()->count(4)->create();
        Article::factory()->featured()->count(8)->create();

        $response = $this->getJson('/api/V1/articles/featured');

        $response->assertOk();
        $this->assertCount(6, $response->json('data'));
    }

    public function test_show_returns_article_by_slug_and_increments_views(): void
    {
        $article = Article::factory()->create(['slug' => 'welcome-post', 'views_count' => 10]);

        $this->getJson('/api/V1/articles/welcome-post')
            ->assertOk()
            ->assertJsonPath('data.slug', 'welcome-post')
            ->assertJsonPath('data.views_count', 11);

        $this->assertSame(11, $article->fresh()->views_count);
    }

    public function test_show_returns_404_for_unpublished_article(): void
    {
        Article::factory()->draft()->create(['slug' => 'hidden']);

        $this->getJson('/api/V1/articles/hidden')->assertNotFound();
    }

    public function test_types_endpoint_lists_enum_values(): void
    {
        $this->getJson('/api/V1/articles/types')
            ->assertOk()
            ->assertJsonFragment(['value' => ArticleTypeEnum::Blog->value])
            ->assertJsonFragment(['value' => ArticleTypeEnum::Media->value])
            ->assertJsonFragment(['value' => ArticleTypeEnum::News->value]);
    }
}
