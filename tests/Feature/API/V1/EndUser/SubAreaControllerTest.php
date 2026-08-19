<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\City;
use App\Models\Compound;
use App\Models\Faq;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CityControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_city_show_returns_details_with_counts(): void
    {
        $city = City::factory()->create([
            'latitude' => 30.8418,
            'longitude' => 28.9522,
        ]);

        $response = $this->getJson("/api/V1/cities/{$city->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $city->id)
            ->assertJsonPath('data.latitude', 30.8418)
            ->assertJsonPath('data.longitude', 28.9522)
            ->assertJsonPath('data.units_count', 0)
            ->assertJsonPath('data.compounds_count', 0)
            ->assertJsonStructure([
                'data' => [
                    'id', 'name', 'description', 'latitude', 'longitude',
                    'state', 'units_count', 'compounds_count', 'image_url',
                ],
            ]);
    }

    public function test_city_show_returns_404_for_missing_city(): void
    {
        $this->getJson('/api/V1/cities/999999')
            ->assertNotFound();
    }

    public function test_city_offers_returns_only_active_offers_in_city(): void
    {
        $city = City::factory()->create();
        $otherCity = City::factory()->create();

        $compound = Compound::factory()->create(['city_id' => $city->id]);
        $foreignCompound = Compound::factory()->create(['city_id' => $otherCity->id]);

        $activeOffer = Offer::factory()->create(['compound_id' => $compound->id]);
        Offer::factory()->inactive()->create(['compound_id' => $compound->id]);
        Offer::factory()->create(['compound_id' => $foreignCompound->id]);

        $response = $this->getJson("/api/V1/cities/{$city->id}/offers");

        $response->assertOk()
            ->assertJsonPath('data.meta.total', 1)
            ->assertJsonPath('data.data.0.id', $activeOffer->id);
    }

    public function test_city_compounds_scoped_to_city(): void
    {
        $city = City::factory()->create();
        $otherCity = City::factory()->create();

        Compound::factory()->count(2)->create(['city_id' => $city->id]);
        Compound::factory()->create(['city_id' => $otherCity->id]);

        $response = $this->getJson("/api/V1/cities/{$city->id}/compounds");

        $response->assertOk()
            ->assertJsonPath('data.meta.total', 2);
    }

    public function test_city_faqs_returns_only_active_sorted_faqs(): void
    {
        $city = City::factory()->create();

        $secondFaq = Faq::factory()->create([
            'faqable_type' => (new City)->getMorphClass(),
            'faqable_id' => $city->id,
            'sort_order' => 2,
        ]);
        $firstFaq = Faq::factory()->create([
            'faqable_type' => (new City)->getMorphClass(),
            'faqable_id' => $city->id,
            'sort_order' => 1,
        ]);
        Faq::factory()->inactive()->create([
            'faqable_type' => (new City)->getMorphClass(),
            'faqable_id' => $city->id,
            'sort_order' => 3,
        ]);

        $response = $this->getJson("/api/V1/cities/{$city->id}/faqs");

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $firstFaq->id)
            ->assertJsonPath('data.1.id', $secondFaq->id);
    }
}
