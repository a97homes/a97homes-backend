<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Chatbot;

use App\Models\ChatbotMessage;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ChatbotMessageResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ChatbotMessage $message */
        $message = $this->resource;

        return [
            'id' => $message->id,
            'role' => $message->role,
            'content' => $message->content,
            'intent' => $message->intent,
            'metadata' => $message->metadata,
            'created_at' => $message->created_at,
        ];
    }
}
