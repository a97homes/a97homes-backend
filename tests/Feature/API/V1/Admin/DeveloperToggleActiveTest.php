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

class DeveloperToggleActiveTest extends TestCase
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

    public function test_toggle_deactivates_active_developer(): void
    {
        $this->actingAsAdmin();
        $developer = Developer::factory()->create();

        $this->patchJson("/api/admin/V1/developers/{$developer->id}/toggle-active")
            ->assertOk()
            ->assertJsonPath('data.is_active', false);

        $this->assertFalse($developer->refresh()->is_active);
    }

    public function test_toggle_activates_inactive_developer(): void
    {
        $this->actingAsAdmin();
        $developer = Developer::factory()->inactive()->create();

        $this->patchJson("/api/admin/V1/developers/{$developer->id}/toggle-active")
            ->assertOk()
            ->assertJsonPath('data.is_active', true);

        $this->assertTrue($developer->refresh()->is_active);
    }

    public function test_toggle_requires_authentication(): void
    {
        $developer = Developer::factory()->create();

        $this->patchJson("/api/admin/V1/developers/{$developer->id}/toggle-active")
            ->assertUnauthorized();
    }
}
