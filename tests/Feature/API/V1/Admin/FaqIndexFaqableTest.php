<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Models\Area;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Faq;
use App\Models\Role;
use App\Models\SubArea;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FaqIndexFaqableTest extends TestCase
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

    public function test_index_includes_faqable_for_sub_area(): void
    {
        $this->actingAsAdmin();
        $area = Area::factory()->create(['name' => ['en' => 'Cairo', 'ar' => 'القاهرة']]);
        $subArea = SubArea::factory()->create([
            'name' => ['en' => 'New Cairo', 'ar' => 'القاهرة الجديدة'],
            'area_id' => $area->id,
        ]);
        $faq = Faq::factory()->create([
            'faqable_type' => $subArea->getMorphClass(),
            'faqable_id' => $subArea->id,
        ]);

        $this->getJson('/api/admin/V1/faqs')
            ->assertOk()
            ->assertJsonPath('data.0.id', $faq->id)
            ->assertJsonPath('data.0.faqable_type', $subArea->getMorphClass())
            ->assertJsonPath('data.0.faqable_id', $subArea->id)
            ->assertJsonPath('data.0.faqable.type', $subArea->getMorphClass())
            ->assertJsonPath('data.0.faqable.id', $subArea->id)
            ->assertJsonPath('data.0.faqable.name.en', 'New Cairo')
            ->assertJsonPath('data.0.faqable.area.id', $area->id)
            ->assertJsonPath('data.0.faqable.area.name.en', 'Cairo');
    }

    public function test_index_includes_faqable_for_compound(): void
    {
        $this->actingAsAdmin();
        $area = Area::factory()->create(['name' => ['en' => 'Giza', 'ar' => 'الجيزة']]);
        $subArea = SubArea::factory()->create([
            'name' => ['en' => 'Sheikh Zayed', 'ar' => 'الشيخ زايد'],
            'area_id' => $area->id,
        ]);
        $developer = Developer::factory()->create(['name' => 'SODIC']);
        $compound = Compound::factory()->create([
            'name' => 'Palm Hills',
            'developer_id' => $developer->id,
            'sub_area_id' => $subArea->id,
        ]);
        Faq::factory()->create([
            'faqable_type' => $compound->getMorphClass(),
            'faqable_id' => $compound->id,
        ]);

        $this->getJson('/api/admin/V1/faqs?filter[faqable_type]='.$compound->getMorphClass())
            ->assertOk()
            ->assertJsonPath('data.0.faqable.type', $compound->getMorphClass())
            ->assertJsonPath('data.0.faqable.id', $compound->id)
            ->assertJsonPath('data.0.faqable.name', 'Palm Hills')
            ->assertJsonPath('data.0.faqable.developer.id', $developer->id)
            ->assertJsonPath('data.0.faqable.developer.name.en', 'SODIC')
            ->assertJsonPath('data.0.faqable.sub_area.id', $subArea->id)
            ->assertJsonPath('data.0.faqable.sub_area.name.en', 'Sheikh Zayed')
            ->assertJsonPath('data.0.faqable.area.id', $area->id)
            ->assertJsonPath('data.0.faqable.area.name.en', 'Giza');
    }
}
