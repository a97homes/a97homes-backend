<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Notification\BroadcastNotificationRequest;
use App\Models\User\User;
use App\Notifications\AdminBroadcastNotification;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

class NotificationBroadcastController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_NOTIFICATIONS_BROADCAST]), only: ['broadcast']),
        ];
    }

    public function broadcast(BroadcastNotificationRequest $request): JsonResponse
    {
        $data = $request->validated();

        $notification = new AdminBroadcastNotification(
            title: $data['title'],
            body: $data['body'],
            link: $data['link'] ?? null,
        );

        $userIds = $data['user_ids'] ?? null;
        $count = 0;

        $query = User::query();
        if (is_array($userIds) && count($userIds) > 0) {
            $query->whereIn('id', $userIds);
        }

        $query->chunkById(200, function ($users) use ($notification, &$count): void {
            Notification::send($users, $notification);
            $count += $users->count();
        });

        return $this->ok(
            message: __('messages.notification_broadcast_queued'),
            data: ['recipients' => $count],
        );
    }
}
