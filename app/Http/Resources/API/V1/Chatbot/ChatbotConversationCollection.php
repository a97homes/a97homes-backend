<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Chatbot;

use App\Http\Resources\BasePaginationResource;

class ChatbotConversationCollection extends BasePaginationResource
{
    public $collects = ChatbotConversationResource::class;
}
