<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Offer;
use App\Models\Role;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class OfferDeveloperLinkTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): void
    {
        Role::firstOrCreate([
            'name' => UserRoleEnum::ADMIN->value,
            'guard_name' => Config::get('auth.defaults.guard'),
        ]);

        $admin = User::factory()->create();
        $admin->assignRole(UserRoleEnum::ADMIN->value);
        Sanctum::actingAs($admin);
    }

    public function test_store_offer_linked_to_developer(): void
    {
        $this->actingAsAdmin();
        $developer = Developer::factory()->create();

        $response = $this->postJson('/api/admin/V1/offers', [
            'developer_id' => $developer->id,
            'installment_years' => 8,
            'monthly_payment' => 20000,
            'description' => ['ar' => 'عرض', 'en' => 'Offer'],
        ])->assertOk();

        $offer = Offer::findOrFail($response->json('data.id'));
        $this->assertSame($developer->id, $offer->developer_id);
        $this->assertNull($offer->compound_id);
    }

    public function test_store_offer_requires_compound_or_developer(): void
    {
        $this->actingAsAdmin();

        $errors = $this->postJson('/api/admin/V1/offers', [
            'installment_years' => 8,
        ])->assertUnprocessable()->json('message');

        $this->assertArrayHasKey('compound_id', $errors);
    }

    public function test_store_offer_rejects_both_compound_and_developer(): void
    {
        $this->actingAsAdmin();
        $developer = Developer::factory()->create();
        $compound = Compound::factory()->create();

        $this->postJson('/api/admin/V1/offers', [
            'compound_id' => $compound->id,
            'developer_id' => $developer->id,
        ])->assertUnprocessable();
    }

    public function test_index_filters_offers_by_developer(): void
    {
        $this->actingAsAdmin();
        $developer = Developer::factory()->create();
        $offer = Offer::factory()->create([
            'compound_id' => null,
            'developer_id' => $developer->id,
        ]);
        Offer::factory()->create();

        $this->getJson('/api/admin/V1/offers?filter[developer_id]='.$developer->id)
            ->assertOk()
            ->assertJsonCount(1, 'data.data')
            ->assertJsonPath('data.data.0.id', $offer->id);
    }
}
