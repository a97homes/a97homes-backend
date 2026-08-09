<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\City;
use App\Models\Compound;
use App\Models\CompoundReview;
use App\Models\Developer;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class CompoundReviewSimilarTest extends TestCase
{
    use RefreshDatabase;

    public function test_reviews_endpoint_returns_paginated_reviews_newest_first(): void
    {
        $compound = Compound::factory()->create();

        $older = CompoundReview::factory()->create(['compound_id' => $compound->id, 'created_at' => now()->subDays(3)]);
        $newer = CompoundReview::factory()->create(['compound_id' => $compound->id, 'created_at' => now()->subHour()]);
        CompoundReview::factory()->create(); // unrelated

        $response = $this->getJson("/api/V1/compounds/{$compound->id}/reviews");

        $response->assertOk()
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.data.0.id', $newer->id)
            ->assertJsonPath('data.data.1.id', $older->id);
    }

    public function test_authenticated_user_can_post_review(): void
    {
        $compound = Compound::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->postJson("/api/V1/compounds/{$compound->id}/reviews", [
            'title' => 'Great place',
            'comment' => 'Loved the amenities',
            'overall_rating' => 5,
            'location_rating' => 4,
        ])->assertOk();

        $this->assertDatabaseHas('compound_reviews', [
            'compound_id' => $compound->id,
            'overall_rating' => 5,
            'title' => 'Great place',
        ]);
    }

    public function test_review_posting_is_unique_per_user_and_updates_existing(): void
    {
        $compound = Compound::factory()->create();
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson("/api/V1/compounds/{$compound->id}/reviews", [
            'overall_rating' => 3,
        ])->assertOk();

        $this->postJson("/api/V1/compounds/{$compound->id}/reviews", [
            'overall_rating' => 5,
            'title' => 'Actually great',
        ])->assertOk();

        $this->assertSame(1, CompoundReview::query()->where('compound_id', $compound->id)->where('user_id', $user->id)->count());
        $this->assertSame(5, CompoundReview::query()->where('user_id', $user->id)->value('overall_rating'));
    }

    public function test_unauthenticated_review_post_returns_401(): void
    {
        $compound = Compound::factory()->create();

        $this->postJson("/api/V1/compounds/{$compound->id}/reviews", [
            'overall_rating' => 5,
        ])->assertUnauthorized();
    }

    public function test_review_rating_out_of_range_is_422(): void
    {
        $compound = Compound::factory()->create();
        Sanctum::actingAs(User::factory()->create());

        $this->postJson("/api/V1/compounds/{$compound->id}/reviews", [
            'overall_rating' => 6,
        ])->assertUnprocessable();

        $this->postJson("/api/V1/compounds/{$compound->id}/reviews", [
            'overall_rating' => 0,
        ])->assertUnprocessable();
    }

    public function test_similar_endpoint_prioritises_same_city_and_excludes_self(): void
    {
        $city = City::factory()->create();
        $otherCity = City::factory()->create();
        $developer = Developer::factory()->create();

        $target = Compound::factory()->create(['city_id' => $city->id, 'developer_id' => $developer->id]);

        Compound::factory()->count(3)->create(['city_id' => $city->id]);
        Compound::factory()->create(['city_id' => $otherCity->id, 'developer_id' => $developer->id]);
        Compound::factory()->create(['city_id' => $otherCity->id]); // neither matches, should be excluded

        $response = $this->getJson("/api/V1/compounds/{$target->id}/similar");

        $response->assertOk();
        $data = $response->json('data');
        $this->assertCount(4, $data);
        $this->assertNotContains($target->id, array_column($data, 'id'));
        // first three should be same-city matches
        $this->assertSame($city->id, $data[0]['city']['id']);
        $this->assertSame($city->id, $data[1]['city']['id']);
        $this->assertSame($city->id, $data[2]['city']['id']);
    }

    public function test_show_exposes_reviews_count_and_average_rating(): void
    {
        $compound = Compound::factory()->create();
        CompoundReview::factory()->create(['compound_id' => $compound->id, 'overall_rating' => 5]);
        CompoundReview::factory()->create(['compound_id' => $compound->id, 'overall_rating' => 3]);

        $this->getJson("/api/V1/compounds/{$compound->id}")
            ->assertOk()
            ->assertJsonPath('data.reviews_count', 2)
            ->assertJsonPath('data.average_rating', 4);
    }
}
