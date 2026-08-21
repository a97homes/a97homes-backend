<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Models\Attribute;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Property;
use App\Models\PropertyType;
use App\Models\Role;
use App\Models\SubArea;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ContactMethodsTest extends TestCase
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

    public function test_developer_create_update_remove_and_serialize_contact_methods(): void
    {
        $this->actingAsAdmin();

        $response = $this->postJson('/api/admin/V1/developers', [
            'name' => ['ar' => 'Developer', 'en' => 'Developer'],
            'about' => ['ar' => 'About', 'en' => 'About'],
            'phones' => [
                ['country_code' => '+20', 'number' => '100 123 4567', 'is_primary' => true],
                ['country_code' => '20', 'number' => '+201111234567'],
            ],
            'whatsapp_numbers' => [
                ['country_code' => '+20', 'number' => '1221234567', 'is_primary' => true],
            ],
        ])->assertOk();

        $developerId = $response->json('data.id');
        $response->assertJsonPath('data.phones.0.number', '1001234567')
            ->assertJsonPath('data.phones.1.number', '1111234567')
            ->assertJsonPath('data.whatsapp_numbers.0.country_code', '+20')
            ->assertJsonPath('data.phone', '+201001234567');

        $this->assertDatabaseHas('contact_methods', [
            'contactable_type' => 'developer',
            'contactable_id' => $developerId,
            'type' => 'phone',
            'country_code' => '+20',
            'number' => '1001234567',
            'is_primary' => true,
        ]);

        $this->patchJson("/api/admin/V1/developers/{$developerId}", [
            'phones' => [
                ['country_code' => '+20', 'number' => '1551234567'],
            ],
            'whatsapp_numbers' => [],
        ])->assertOk()
            ->assertJsonCount(1, 'data.phones')
            ->assertJsonCount(0, 'data.whatsapp_numbers')
            ->assertJsonPath('data.phones.0.is_primary', true);

        $this->assertDatabaseMissing('contact_methods', [
            'contactable_type' => 'developer',
            'contactable_id' => $developerId,
            'number' => '1001234567',
        ]);
        $this->assertDatabaseMissing('contact_methods', [
            'contactable_type' => 'developer',
            'contactable_id' => $developerId,
            'type' => 'whatsapp',
        ]);
    }

    public function test_developer_contact_validation_rejects_invalid_duplicate_and_multiple_primary_values(): void
    {
        $this->actingAsAdmin();

        $payload = [
            'name' => ['ar' => 'Developer', 'en' => 'Developer'],
            'about' => ['ar' => 'About', 'en' => 'About'],
            'phones' => [
                ['country_code' => 'abc', 'number' => '1001234567', 'is_primary' => true],
                ['country_code' => '+20', 'number' => '100-123-4567', 'is_primary' => true],
                ['country_code' => '+20', 'number' => '1001234567'],
            ],
        ];

        $errors = $this->postJson('/api/admin/V1/developers', $payload)
            ->assertUnprocessable()
            ->json('message');

        $this->assertArrayHasKey('phones.0.country_code', $errors);
        $this->assertArrayHasKey('phones', $errors);
        $this->assertArrayHasKey('phones.2.number', $errors);
    }

    public function test_property_accepts_contact_methods_and_empty_arrays(): void
    {
        $this->actingAsAdmin();
        $subArea = SubArea::factory()->create();
        $compound = Compound::factory()->create(['sub_area_id' => $subArea->id]);
        $propertyType = PropertyType::query()->create([
            'name' => ['ar' => 'Apartment', 'en' => 'Apartment'],
        ]);
        $attribute = Attribute::query()->create([
            'name' => ['ar' => 'Area', 'en' => 'Area'],
            'slug' => 'area',
            'type' => 'number',
        ]);

        $payload = [
            'name' => ['ar' => 'Apartment', 'en' => 'Apartment'],
            'attributes_ids' => [$attribute->id],
            'attribute_values' => [$attribute->id => '120'],
            'sub_area_id' => $subArea->id,
            'property_type_id' => $propertyType->id,
            'compound_id' => $compound->id,
            'address' => 'Test address',
            'price' => 1000000,
            'latitude' => 30.0123456,
            'longitude' => 31.0123456,
            'phones' => [
                ['country_code' => '+20', 'number' => '1001234567'],
            ],
            'whatsapp_numbers' => [
                ['country_code' => '+20', 'number' => '1221234567'],
            ],
        ];

        $response = $this->postJson('/api/admin/V1/properties', $payload)->assertOk();

        $propertyId = $response->json('data.id');
        $response->assertJsonPath('data.phones.0.is_primary', true)
            ->assertJsonPath('data.whatsapp_numbers.0.number', '1221234567');

        $this->patchJson("/api/admin/V1/properties/{$propertyId}", array_merge($payload, [
            'name' => ['ar' => 'Apartment 2', 'en' => 'Apartment 2'],
            'phones' => [],
            'whatsapp_numbers' => [],
        ]))->assertOk()
            ->assertJsonCount(0, 'data.phones')
            ->assertJsonCount(0, 'data.whatsapp_numbers');

        $this->assertSame(0, Property::findOrFail($propertyId)->contactMethods()->count());
    }

    public function test_compound_accepts_multiple_contact_methods_and_updates_primary(): void
    {
        $this->actingAsAdmin();
        $developer = Developer::factory()->create();

        $response = $this->postJson('/api/admin/V1/compounds', [
            'name' => 'Compound One',
            'developer_id' => $developer->id,
            'phones' => [
                ['country_code' => '+20', 'number' => '1001234567'],
                ['country_code' => '+20', 'number' => '1111234567', 'is_primary' => true],
            ],
            'whatsapp_numbers' => [],
        ])->assertOk();

        $compoundId = $response->json('data.id');
        $response->assertJsonPath('data.phones.0.is_primary', false)
            ->assertJsonPath('data.phones.1.is_primary', true);

        $this->patchJson("/api/admin/V1/compounds/{$compoundId}", [
            'phones' => [
                ['country_code' => '+20', 'number' => '1991234567'],
            ],
        ])->assertOk()
            ->assertJsonPath('data.phones.0.number', '1991234567')
            ->assertJsonPath('data.phones.0.is_primary', true);

        $this->assertSame(1, Compound::findOrFail($compoundId)->phones()->count());
    }
}
