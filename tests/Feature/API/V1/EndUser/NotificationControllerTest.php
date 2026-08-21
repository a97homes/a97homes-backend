<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class NotificationControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_unauthenticated_endpoints_return_401(): void
    {
        $this->getJson('/api/V1/notifications')->assertUnauthorized();
        $this->getJson('/api/V1/notifications/unread-count')->assertUnauthorized();
        $this->postJson('/api/V1/notifications/mark-all-as-read')->assertUnauthorized();
    }

    public function test_list_returns_only_current_users_notifications_newest_first(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $older = $this->insertNotification($user, 'Promo', now()->subDay());
        $newer = $this->insertNotification($user, 'Reminder', now()->subHour());
        $this->insertNotification($other, 'Stranger', now());

        Sanctum::actingAs($user);

        $response = $this->getJson('/api/V1/notifications');

        $response->assertOk()
            ->assertJsonPath('data.meta.total', 2)
            ->assertJsonPath('data.data.0.id', $newer)
            ->assertJsonPath('data.data.1.id', $older);
    }

    public function test_list_filter_unread_and_read(): void
    {
        $user = User::factory()->create();
        $read = $this->insertNotification($user, 'Seen', now()->subHour(), readAt: now()->subMinutes(30));
        $unread = $this->insertNotification($user, 'New', now());

        Sanctum::actingAs($user);

        $this->getJson('/api/V1/notifications?filter=unread')
            ->assertOk()
            ->assertJsonPath('data.meta.total', 1)
            ->assertJsonPath('data.data.0.id', $unread);

        $this->getJson('/api/V1/notifications?filter=read')
            ->assertOk()
            ->assertJsonPath('data.meta.total', 1)
            ->assertJsonPath('data.data.0.id', $read);
    }

    public function test_unread_count(): void
    {
        $user = User::factory()->create();
        $this->insertNotification($user, 'A', now());
        $this->insertNotification($user, 'B', now());
        $this->insertNotification($user, 'Seen', now(), readAt: now());

        Sanctum::actingAs($user);

        $this->getJson('/api/V1/notifications/unread-count')
            ->assertOk()
            ->assertJsonPath('data.unread_count', 2);
    }

    public function test_mark_as_read_only_affects_owned_notification(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $mine = $this->insertNotification($user, 'Mine', now());
        $theirs = $this->insertNotification($other, 'Theirs', now());

        Sanctum::actingAs($user);

        $this->postJson("/api/V1/notifications/{$mine}/mark-as-read")->assertOk();
        $this->assertNotNull(DB::table('notifications')->where('id', $mine)->value('read_at'));

        $this->postJson("/api/V1/notifications/{$theirs}/mark-as-read")->assertNotFound();
        $this->assertNull(DB::table('notifications')->where('id', $theirs)->value('read_at'));
    }

    public function test_mark_all_as_read(): void
    {
        $user = User::factory()->create();
        $this->insertNotification($user, 'A', now());
        $this->insertNotification($user, 'B', now());
        $this->insertNotification($user, 'C', now(), readAt: now());

        Sanctum::actingAs($user);

        $this->postJson('/api/V1/notifications/mark-all-as-read')->assertOk();

        $this->assertSame(0, DB::table('notifications')
            ->where('notifiable_id', $user->id)
            ->whereNull('read_at')
            ->count());
    }

    public function test_destroy_only_deletes_own_notification(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $mine = $this->insertNotification($user, 'Mine', now());
        $theirs = $this->insertNotification($other, 'Theirs', now());

        Sanctum::actingAs($user);

        $this->deleteJson("/api/V1/notifications/{$mine}")->assertOk();
        $this->assertDatabaseMissing('notifications', ['id' => $mine]);

        $this->deleteJson("/api/V1/notifications/{$theirs}")->assertNotFound();
        $this->assertDatabaseHas('notifications', ['id' => $theirs]);
    }

    private function insertNotification(User $user, string $title, $createdAt, $readAt = null): string
    {
        $id = (string) Str::uuid();

        DB::table('notifications')->insert([
            'id' => $id,
            'type' => 'App\\Notifications\\TestNotification',
            'notifiable_type' => $user->getMorphClass(),
            'notifiable_id' => $user->id,
            'data' => json_encode(['title' => $title]),
            'read_at' => $readAt,
            'created_at' => $createdAt,
            'updated_at' => $createdAt,
        ]);

        return $id;
    }
}
