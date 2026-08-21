<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Enums\SavedSearchTypeEnum;
use App\Models\Compound;
use App\Models\SavedSearch;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class SavedSearchControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_endpoints_return_401(): void
    {
        $this->getJson('/api/V1/saved-searches')->assertUnauthorized();
        $this->postJson('/api/V1/saved-searches', [])->assertUnauthorized();
    }

    public function test_store_saves_search_for_authenticated_user(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $payload = [
            'name' => 'Affordable Cairo compounds',
            'type' => 'compound',
            'criteria' => ['filter' => ['sub_area_id' => 1], 'sort' => '-id'],
            'notify_by_email' => true,
        ];

        $response = $this->postJson('/api/V1/saved-searches', $payload);

        $response->assertOk()
            ->assertJsonPath('data.name', 'Affordable Cairo compounds')
            ->assertJsonPath('data.type', 'compound');

        $this->assertDatabaseHas('saved_searches', [
            'user_id' => $user->id,
            'name' => 'Affordable Cairo compounds',
            'type' => 'compound',
            'notify_by_email' => 1,
        ]);
    }

    public function test_index_only_returns_current_users_searches(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        SavedSearch::factory()->count(2)->create(['user_id' => $user->id]);
        SavedSearch::factory()->count(3)->create(['user_id' => $other->id]);

        Sanctum::actingAs($user);

        $this->getJson('/api/V1/saved-searches')
            ->assertOk()
            ->assertJsonPath('data.meta.total', 2);
    }

    public function test_show_and_update_and_delete_respect_ownership(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $mine = SavedSearch::factory()->create(['user_id' => $user->id, 'name' => 'Mine']);
        $theirs = SavedSearch::factory()->create(['user_id' => $other->id, 'name' => 'Theirs']);

        Sanctum::actingAs($user);

        $this->getJson("/api/V1/saved-searches/{$mine->id}")->assertOk();
        $this->getJson("/api/V1/saved-searches/{$theirs->id}")->assertNotFound();

        $this->putJson("/api/V1/saved-searches/{$mine->id}", ['name' => 'Renamed'])
            ->assertOk()
            ->assertJsonPath('data.name', 'Renamed');

        $this->putJson("/api/V1/saved-searches/{$theirs->id}", ['name' => 'Nope'])
            ->assertNotFound();

        $this->deleteJson("/api/V1/saved-searches/{$theirs->id}")->assertNotFound();
        $this->assertDatabaseHas('saved_searches', ['id' => $theirs->id]);

        $this->deleteJson("/api/V1/saved-searches/{$mine->id}")->assertOk();
        $this->assertDatabaseMissing('saved_searches', ['id' => $mine->id]);
    }

    public function test_store_rejects_invalid_type(): void
    {
        Sanctum::actingAs(User::factory()->create());

        $this->postJson('/api/V1/saved-searches', [
            'type' => 'bogus',
            'criteria' => [],
        ])->assertUnprocessable();
    }

    public function test_run_updates_last_checked_at_and_returns_results(): void
    {
        $user = User::factory()->create();
        Compound::factory()->count(2)->create();

        $search = SavedSearch::factory()->create([
            'user_id' => $user->id,
            'type' => SavedSearchTypeEnum::Compound,
            'criteria' => [],
            'last_checked_at' => null,
        ]);

        Sanctum::actingAs($user);

        $response = $this->postJson("/api/V1/saved-searches/{$search->id}/run");

        $response->assertOk()
            ->assertJsonPath('data.meta.total', 2);

        $this->assertNotNull($search->fresh()->last_checked_at);
    }

    public function test_run_404_for_other_users_search(): void
    {
        $user = User::factory()->create();
        $search = SavedSearch::factory()->create(['user_id' => User::factory()->create()->id]);

        Sanctum::actingAs($user);

        $this->postJson("/api/V1/saved-searches/{$search->id}/run")->assertNotFound();
    }
}
