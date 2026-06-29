<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Models\Developer;
use App\Models\Role;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class DeveloperStoreFieldsTest extends TestCase
{
    use RefreshDatabase;

    private function actingAsAdmin(): User
    {
        Role::firstOrCreate([
            'name' => UserRoleEnum::ADMIN->value,
            'guard_name' => Config::get('auth.defaults.guard'),
        ]);

        $admin = User::factory()->create();
        $admin->assignRole(UserRoleEnum::ADMIN->value);
        Sanctum::actingAs($admin);

        return $admin;
    }

    public function test_store_creates_developer_with_all_fields(): void
    {
        $this->actingAsAdmin();

        $payload = [
            'name' => ['ar' => 'مطور', 'en' => 'Developer One'],
            'about' => ['ar' => 'نبذة', 'en' => 'About'],
            'whatsapp' => '+201000000000',
            'phone' => '+201111111111',
            'is_active' => true,
        ];

        $response = $this->postJson('/api/admin/V1/developers', $payload)->assertOk();

        $developer = Developer::findOrFail($response->json('data.id'));

        $this->assertSame('Developer One', $developer->getTranslation('name', 'en'));
        $this->assertSame('مطور', $developer->getTranslation('name', 'ar'));
        $this->assertSame('About', $developer->getTranslation('about', 'en'));
        $this->assertSame('+201000000000', $developer->whatsapp);
        $this->assertSame('+201111111111', $developer->phone);
    }

    public function test_store_requires_arabic_name_and_about(): void
    {
        $this->actingAsAdmin();

        $errors = $this->postJson('/api/admin/V1/developers', [
            'name' => ['en' => 'Only English'],
            'about' => ['en' => 'Only English'],
        ])
            ->assertUnprocessable()
            ->json('message');

        $this->assertArrayHasKey('name.ar', $errors);
        $this->assertArrayHasKey('about.ar', $errors);
    }
}
