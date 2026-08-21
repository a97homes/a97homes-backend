<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Notification\NotificationCollection;
use App\Http\Resources\API\V1\Notification\NotificationResource;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Paginated notifications for the authenticated user, newest first.
     *
     * Query params:
     *   filter = all | unread | read  (default all)
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $filter = $request->query('filter', 'all');

        $query = match ($filter) {
            'unread' => $user->unreadNotifications(),
            'read' => $user->readNotifications(),
            default => $user->notifications(),
        };

        $notifications = $query->latest()->macroPaginate();

        return $this->ok(data: new NotificationCollection($notifications));
    }

    /**
     * Unread notification count for the header badge.
     */
    public function unreadCount(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        return $this->ok(data: [
            'unread_count' => $user->unreadNotifications()->count(),
        ]);
    }

    /**
     * Mark a single notification as read. 404 when the id does not
     * belong to the authenticated user — never leak other users' ids.
     */
    public function markAsRead(Request $request, string $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $notification = $user->notifications()->where('id', $id)->first();

        if ($notification === null) {
            return $this->notFound(__('messages.notification_not_found'));
        }

        if ($notification->read_at === null) {
            $notification->markAsRead();
        }

        return $this->ok(
            message: __('messages.notification_marked_read'),
            data: NotificationResource::make($notification->refresh()),
        );
    }

    /**
     * Mark every unread notification as read for the current user.
     */
    public function markAllAsRead(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $user->unreadNotifications->markAsRead();

        return $this->ok(message: __('messages.notifications_all_marked_read'));
    }

    /**
     * Delete one of the current user's notifications.
     */
    public function destroy(Request $request, string $id): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $deleted = $user->notifications()->where('id', $id)->delete();

        if ($deleted === 0) {
            return $this->notFound(__('messages.notification_not_found'));
        }

        return $this->ok(message: __('messages.notification_deleted'));
    }
}
