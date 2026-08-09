<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\City;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Faq;
use App\Models\Offer;
use App\Models\Property;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DeveloperShowFieldsTest extends TestCase
{
    use RefreshDatabase;

    public function test_show_returns_all_developer_fields(): void
    {
        $developer = Developer::factory()->create([
            'name' => ['en' => 'Skyline Group', 'ar' => 'مجموعة سكايلاين'],
            'about' => ['en' => 'About us', 'ar' => 'من نحن'],
            'whatsapp' => '+201000000000',
            'phone' => '+201111111111',
        ]);

        $city = City::factory()->create();
        Compound::factory()->create([
            'developer_id' => $developer->id,
            'city_id' => $city->id,
        ]);

        $offer = Offer::factory()->create([
            'compound_id' => null,
            'developer_id' => $developer->id,
        ]);

        $faq = Faq::factory()->create([
            'faqable_type' => $developer->getMorphClass(),
            'faqable_id' => $developer->id,
        ]);

        $this->getJson("/api/V1/developers/{$developer->id}")
            ->assertOk()
            ->assertJsonPath('data.id', $developer->id)
            ->assertJsonPath('data.name.en', 'Skyline Group')
            ->assertJsonPath('data.name.ar', 'مجموعة سكايلاين')
            ->assertJsonPath('data.about.en', 'About us')
            ->assertJsonPath('data.whatsapp', '+201000000000')
            ->assertJsonPath('data.phone', '+201111111111')
            ->assertJsonPath('data.offers.0.id', $offer->id)
            ->assertJsonPath('data.faqs.0.id', $faq->id)
            ->assertJsonPath('data.areas.0.id', $city->id);
    }

    public function test_show_returns_compounds_and_units_counts(): void
    {
        $developer = Developer::factory()->create();
        $city = City::factory()->create();

        $compounds = Compound::factory()->count(2)->create([
            'developer_id' => $developer->id,
            'city_id' => $city->id,
        ]);

        foreach ($compounds as $compound) {
            Property::query()->create([
                'compound_id' => $compound->id,
                'address' => 'Test address',
            ]);
            Property::query()->create([
                'compound_id' => $compound->id,
                'address' => 'Test address',
            ]);
        }

        $this->getJson("/api/V1/developers/{$developer->id}")
            ->assertOk()
            ->assertJsonPath('data.compounds_count', 2)
            ->assertJsonPath('data.units_count', 4);
    }

    public function test_show_deduplicates_areas_across_compounds(): void
    {
        $developer = Developer::factory()->create();
        $city = City::factory()->create();

        Compound::factory()->count(3)->create([
            'developer_id' => $developer->id,
            'city_id' => $city->id,
        ]);

        $this->getJson("/api/V1/developers/{$developer->id}")
            ->assertOk()
            ->assertJsonCount(1, 'data.areas')
            ->assertJsonPath('data.areas.0.id', $city->id);
    }

    public function test_show_hides_inactive_offers_and_faqs(): void
    {
        $developer = Developer::factory()->create();

        Offer::factory()->inactive()->create([
            'compound_id' => null,
            'developer_id' => $developer->id,
        ]);
        Faq::factory()->inactive()->create([
            'faqable_type' => $developer->getMorphClass(),
            'faqable_id' => $developer->id,
        ]);

        $this->getJson("/api/V1/developers/{$developer->id}")
            ->assertOk()
            ->assertJsonCount(0, 'data.offers')
            ->assertJsonCount(0, 'data.faqs');
    }
}
