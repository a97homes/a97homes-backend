<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Chatbot;

use App\Models\ChatbotConversation;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatbotConversationResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ChatbotConversation $conversation */
        $conversation = $this->resource;

        return [
            'id' => $conversation->id,
            'session_id' => $conversation->session_id,
            'locale' => $conversation->locale,
            'messages' => ChatbotMessageResource::collection($this->whenLoaded('messages')),
            'created_at' => $conversation->created_at,
            'updated_at' => $conversation->updated_at,
        ];
    }
}
