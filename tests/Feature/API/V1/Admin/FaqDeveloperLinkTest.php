<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Models\Developer;
use App\Models\Faq;
use App\Models\Role;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Config;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class FaqDeveloperLinkTest extends TestCase
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

    public function test_store_faq_linked_to_developer(): void
    {
        $this->actingAsAdmin();
        $developer = Developer::factory()->create();

        $response = $this->postJson('/api/admin/V1/faqs', [
            'faqable_type' => 'developer',
            'faqable_id' => $developer->id,
            'question' => ['ar' => 'سؤال', 'en' => 'Question?'],
            'answer' => ['ar' => 'جواب', 'en' => 'Answer'],
            'sort_order' => 1,
        ])->assertOk();

        $faq = Faq::findOrFail($response->json('data.id'));
        $this->assertSame($developer->getMorphClass(), $faq->faqable_type);
        $this->assertSame($developer->id, $faq->faqable_id);
        $this->assertInstanceOf(Developer::class, $faq->faqable);
    }

    public function test_store_faq_rejects_unknown_developer(): void
    {
        $this->actingAsAdmin();

        $this->postJson('/api/admin/V1/faqs', [
            'faqable_type' => 'developer',
            'faqable_id' => 999999,
            'question' => ['ar' => 'سؤال', 'en' => 'Question?'],
            'answer' => ['ar' => 'جواب', 'en' => 'Answer'],
        ])->assertUnprocessable();
    }
}
