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

class DeveloperBulkActionsTest extends TestCase
{
    use RefreshDatabase;

    private const BULK_DELETE_URL = '/api/admin/V1/developers/bulk';

    private const BULK_STATUS_URL = '/api/admin/V1/developers/bulk/status';

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

    public function test_admin_can_bulk_delete_developers(): void
    {
        $this->actingAsAdmin();
        $developers = Developer::factory()->count(3)->create();
        $survivor = Developer::factory()->create();

        $this->deleteJson(self::BULK_DELETE_URL, ['ids' => $developers->pluck('id')->all()])
            ->assertOk()
            ->assertJsonPath('data.deleted_count', 3);

        $this->assertSame(0, Developer::query()->whereIn('id', $developers->pluck('id'))->count());
        $this->assertNotNull($survivor->fresh());
    }

    public function test_admin_can_bulk_deactivate_developers(): void
    {
        $this->actingAsAdmin();
        $developers = Developer::factory()->count(2)->create();

        $this->patchJson(self::BULK_STATUS_URL, [
            'ids' => $developers->pluck('id')->all(),
            'is_active' => false,
        ])->assertOk()
            ->assertJsonPath('data.updated_count', 2);

        $developers->each(fn (Developer $developer) => $this->assertFalse($developer->refresh()->is_active));
    }

    public function test_admin_can_bulk_activate_developers(): void
    {
        $this->actingAsAdmin();
        $developers = Developer::factory()->count(2)->inactive()->create();

        $this->patchJson(self::BULK_STATUS_URL, [
            'ids' => $developers->pluck('id')->all(),
            'is_active' => true,
        ])->assertOk()
            ->assertJsonPath('data.updated_count', 2);

        $developers->each(fn (Developer $developer) => $this->assertTrue($developer->refresh()->is_active));
    }

    public function test_bulk_delete_rejects_unknown_ids(): void
    {
        $this->actingAsAdmin();
        $developer = Developer::factory()->create();

        $this->deleteJson(self::BULK_DELETE_URL, ['ids' => [$developer->id, $developer->id + 999]])
            ->assertUnprocessable();

        $this->assertNotNull($developer->fresh());
    }

    public function test_bulk_endpoints_require_ids(): void
    {
        $this->actingAsAdmin();

        $this->deleteJson(self::BULK_DELETE_URL, ['ids' => []])->assertUnprocessable();
        $this->patchJson(self::BULK_STATUS_URL, ['is_active' => true])->assertUnprocessable();
    }

    public function test_bulk_status_requires_is_active(): void
    {
        $this->actingAsAdmin();
        $developer = Developer::factory()->create();

        $this->patchJson(self::BULK_STATUS_URL, ['ids' => [$developer->id]])
            ->assertUnprocessable();
    }

    public function test_bulk_endpoints_require_authentication(): void
    {
        $developer = Developer::factory()->create();

        $this->deleteJson(self::BULK_DELETE_URL, ['ids' => [$developer->id]])->assertUnauthorized();
        $this->patchJson(self::BULK_STATUS_URL, ['ids' => [$developer->id], 'is_active' => false])->assertUnauthorized();
    }
}
