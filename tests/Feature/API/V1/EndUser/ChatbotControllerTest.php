<?php

declare(strict_types=1);

namespace Tests\Feature\API\V1\EndUser;

use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Models\User\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class ChatbotControllerTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_can_send_a_message_and_get_a_reply(): void
    {
        $response = $this->postJson('/api/V1/chatbot/messages', [
            'message' => 'Hello!',
            'locale' => 'en',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.assistant_message.intent', 'greeting')
            ->assertJsonStructure([
                'data' => [
                    'session_id',
                    'conversation_id',
                    'user_message' => ['id', 'role', 'content'],
                    'assistant_message' => ['id', 'role', 'content', 'intent', 'metadata'],
                    'suggestions',
                ],
            ]);

        $this->assertDatabaseCount('chatbot_conversations', 1);
        $this->assertDatabaseCount('chatbot_messages', 2);
    }

    public function test_same_session_id_reuses_conversation(): void
    {
        $first = $this->postJson('/api/V1/chatbot/messages', [
            'message' => 'hi',
            'session_id' => 'abc-123',
            'locale' => 'en',
        ])->assertOk()->json();

        $second = $this->postJson('/api/V1/chatbot/messages', [
            'message' => 'help please',
            'session_id' => 'abc-123',
            'locale' => 'en',
        ])->assertOk()->json();

        $this->assertSame($first['data']['conversation_id'], $second['data']['conversation_id']);
        $this->assertDatabaseCount('chatbot_conversations', 1);
        $this->assertDatabaseCount('chatbot_messages', 4);
    }

    public function test_pricing_intent_is_matched_in_arabic(): void
    {
        $this->postJson('/api/V1/chatbot/messages', [
            'message' => 'ما هو السعر؟',
            'locale' => 'ar',
        ])
            ->assertOk()
            ->assertJsonPath('data.assistant_message.intent', 'pricing');
    }

    public function test_message_is_required(): void
    {
        $this->postJson('/api/V1/chatbot/messages', ['message' => ''])
            ->assertUnprocessable();
    }

    public function test_authenticated_user_conversations_index(): void
    {
        $user = User::factory()->create();
        Sanctum::actingAs($user);

        $this->postJson('/api/V1/chatbot/messages', ['message' => 'hello', 'session_id' => 's1', 'locale' => 'en'])
            ->assertOk();

        $this->postJson('/api/V1/chatbot/messages', ['message' => 'hi', 'session_id' => 's2', 'locale' => 'en'])
            ->assertOk();

        $response = $this->getJson('/api/V1/chatbot/conversations');

        $response->assertOk()
            ->assertJsonPath('data.meta.total', 2);
    }

    public function test_show_conversation_respects_ownership(): void
    {
        $user = User::factory()->create();
        $other = User::factory()->create();

        $mine = ChatbotConversation::create([
            'user_id' => $user->id,
            'session_id' => 'mine',
            'locale' => 'en',
        ]);
        ChatbotMessage::create([
            'conversation_id' => $mine->id,
            'role' => ChatbotMessage::ROLE_USER,
            'content' => 'hey',
        ]);

        $theirs = ChatbotConversation::create([
            'user_id' => $other->id,
            'session_id' => 'theirs',
            'locale' => 'en',
        ]);

        Sanctum::actingAs($user);

        $this->getJson("/api/V1/chatbot/conversations/{$mine->id}")
            ->assertOk()
            ->assertJsonPath('data.session_id', 'mine')
            ->assertJsonCount(1, 'data.messages');

        $this->getJson("/api/V1/chatbot/conversations/{$theirs->id}")
            ->assertNotFound();
    }

    public function test_fallback_reply_when_no_intent_matches(): void
    {
        $response = $this->postJson('/api/V1/chatbot/messages', [
            'message' => 'zzzzzzzz',
            'locale' => 'en',
        ]);

        $response->assertOk()
            ->assertJsonPath('data.assistant_message.intent', 'fallback');
    }
}
