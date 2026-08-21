<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Chatbot\SendMessageRequest;
use App\Http\Resources\API\V1\Chatbot\ChatbotConversationCollection;
use App\Http\Resources\API\V1\Chatbot\ChatbotConversationResource;
use App\Http\Resources\API\V1\Chatbot\ChatbotMessageResource;
use App\Models\ChatbotConversation;
use App\Models\ChatbotMessage;
use App\Services\Chatbot\ChatbotResponder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function __construct(private readonly ChatbotResponder $responder) {}

    /**
     * Send a user message and receive the assistant's reply. Works for
     * both guests (by session_id) and authenticated users. The session_id
     * is echoed back on every response so clients can pin the
     * conversation without creating a new one on the next send.
     */
    public function send(SendMessageRequest $request): JsonResponse
    {
        $locale = $request->validated('locale') ?? app()->getLocale();
        $userId = $request->user()?->id;

        $conversation = ChatbotConversation::findOrCreateForRequest(
            userId: $userId,
            sessionId: $request->validated('session_id'),
            locale: $locale,
        );

        $userMessage = ChatbotMessage::create([
            'conversation_id' => $conversation->id,
            'role' => ChatbotMessage::ROLE_USER,
            'content' => $request->validated('message'),
        ]);

        $history = $conversation->messages()
            ->orderBy('id')
            ->limit(20)
            ->get(['role', 'content'])
            ->map(fn (ChatbotMessage $m) => ['role' => $m->role, 'content' => $m->content])
            ->all();

        $response = $this->responder->reply(
            userMessage: $userMessage->content,
            locale: $locale,
            history: $history,
        );

        $assistantMessage = ChatbotMessage::create([
            'conversation_id' => $conversation->id,
            'role' => ChatbotMessage::ROLE_ASSISTANT,
            'content' => $response['reply'],
            'intent' => $response['intent'],
            'metadata' => array_merge(
                ['suggestions' => $response['suggestions']],
                $response['metadata'] ?? [],
            ),
        ]);

        $conversation->touch();

        return $this->ok(data: [
            'session_id' => $conversation->session_id,
            'conversation_id' => $conversation->id,
            'user_message' => ChatbotMessageResource::make($userMessage),
            'assistant_message' => ChatbotMessageResource::make($assistantMessage),
            'suggestions' => $response['suggestions'],
        ]);
    }

    /**
     * List the authenticated user's conversations (most recently used
     * first). Excludes guest sessions.
     */
    public function conversations(Request $request): JsonResponse
    {
        $conversations = ChatbotConversation::query()
            ->where('user_id', $request->user()->id)
            ->orderByDesc('updated_at')
            ->macroPaginate();

        return $this->ok(data: new ChatbotConversationCollection($conversations));
    }

    /**
     * Show one conversation for the authenticated user with every
     * message inlined. Ownership is enforced — a 404 is returned
     * for ids belonging to other users so ids are not enumerable.
     */
    public function showConversation(Request $request, ChatbotConversation $chatbotConversation): JsonResponse
    {
        if ($chatbotConversation->user_id !== $request->user()->id) {
            return $this->notFound(__('messages.chatbot_conversation_not_found'));
        }

        $chatbotConversation->load(['messages' => fn ($q) => $q->orderBy('id')]);

        return $this->ok(data: ChatbotConversationResource::make($chatbotConversation));
    }
}
