<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\SubArea;
use App\Models\Compound;
use App\Models\Faq;
use App\Models\Offer;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SubAreaControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_sub_area_show_returns_details_with_counts(): void
    {
        $subArea = SubArea::factory()->create([
            'latitude' => 30.8418,
            'longitude' => 28.9522,
        ]);

        $response = $this->getJson("/api/V1/sub-areas/{$subArea->id}");

        $response->assertOk()
            ->assertJsonPath('data.id', $subArea->id)
            ->assertJsonPath('data.latitude', 30.8418)
            ->assertJsonPath('data.longitude', 28.9522)
            ->assertJsonPath('data.units_count', 0)
            ->assertJsonPath('data.compounds_count', 0)
            ->assertJsonStructure([
                'data' => [
                    'id', 'name', 'description', 'latitude', 'longitude',
                    'area', 'units_count', 'compounds_count', 'image_url',
                ],
            ]);
    }

    public function test_sub_area_show_returns_404_for_missing_sub_area(): void
    {
        $this->getJson('/api/V1/sub-areas/999999')
            ->assertNotFound();
    }

    public function test_sub_area_offers_returns_only_active_offers_in_sub_area(): void
    {
        $subArea = SubArea::factory()->create();
        $otherSubArea = SubArea::factory()->create();

        $compound = Compound::factory()->create(['sub_area_id' => $subArea->id]);
        $foreignCompound = Compound::factory()->create(['sub_area_id' => $otherSubArea->id]);

        $activeOffer = Offer::factory()->create(['compound_id' => $compound->id]);
        Offer::factory()->inactive()->create(['compound_id' => $compound->id]);
        Offer::factory()->create(['compound_id' => $foreignCompound->id]);

        $response = $this->getJson("/api/V1/sub-areas/{$subArea->id}/offers");

        $response->assertOk()
            ->assertJsonPath('data.meta.total', 1)
            ->assertJsonPath('data.data.0.id', $activeOffer->id);
    }

    public function test_sub_area_compounds_scoped_to_sub_area(): void
    {
        $subArea = SubArea::factory()->create();
        $otherSubArea = SubArea::factory()->create();

        Compound::factory()->count(2)->create(['sub_area_id' => $subArea->id]);
        Compound::factory()->create(['sub_area_id' => $otherSubArea->id]);

        $response = $this->getJson("/api/V1/sub-areas/{$subArea->id}/compounds");

        $response->assertOk()
            ->assertJsonPath('data.meta.total', 2);
    }

    public function test_sub_area_faqs_returns_only_active_sorted_faqs(): void
    {
        $subArea = SubArea::factory()->create();

        $secondFaq = Faq::factory()->create([
            'faqable_type' => (new SubArea)->getMorphClass(),
            'faqable_id' => $subArea->id,
            'sort_order' => 2,
        ]);
        $firstFaq = Faq::factory()->create([
            'faqable_type' => (new SubArea)->getMorphClass(),
            'faqable_id' => $subArea->id,
            'sort_order' => 1,
        ]);
        Faq::factory()->inactive()->create([
            'faqable_type' => (new SubArea)->getMorphClass(),
            'faqable_id' => $subArea->id,
            'sort_order' => 3,
        ]);

        $response = $this->getJson("/api/V1/sub-areas/{$subArea->id}/faqs");

        $response->assertOk()
            ->assertJsonCount(2, 'data')
            ->assertJsonPath('data.0.id', $firstFaq->id)
            ->assertJsonPath('data.1.id', $secondFaq->id);
    }
}
