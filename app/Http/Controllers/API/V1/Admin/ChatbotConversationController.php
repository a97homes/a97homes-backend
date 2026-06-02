<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Chatbot\ChatbotConversationCollection;
use App\Http\Resources\API\V1\Chatbot\ChatbotConversationResource;
use App\Models\ChatbotConversation;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ChatbotConversationController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CHATBOT_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CHATBOT_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CHATBOT_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $conversations = QueryBuilder::for(ChatbotConversation::class)
            ->allowedFilters([
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('locale'),
                AllowedFilter::partial('session_id'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('created_at'),
                AllowedSort::field('updated_at'),
            ])
            ->withCount('messages')
            ->with('user:id,name,email')
            ->macroPaginate();

        return $this->ok(data: new ChatbotConversationCollection($conversations));
    }

    public function show(ChatbotConversation $chatbotConversation): JsonResponse
    {
        $chatbotConversation->load(['messages' => fn ($q) => $q->orderBy('id'), 'user:id,name,email']);

        return $this->ok(data: ChatbotConversationResource::make($chatbotConversation));
    }

    public function destroy(ChatbotConversation $chatbotConversation): JsonResponse
    {
        $chatbotConversation->delete();

        return $this->ok(message: __('messages.chatbot_conversation_deleted_successfully'));
    }
}
