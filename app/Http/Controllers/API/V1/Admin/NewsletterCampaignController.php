<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Newsletter\SendCampaignRequest;
use App\Models\NewsletterSubscriber;
use App\Notifications\NewsletterCampaignNotification;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Notification;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

class NewsletterCampaignController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.newsletter.send']), only: ['send']),
        ];
    }

    public function send(SendCampaignRequest $request): JsonResponse
    {
        $data = $request->validated();
        $locale = $data['locale'] ?? null;

        $query = NewsletterSubscriber::query()->active();
        if ($locale !== null) {
            $query->where('locale', $locale);
        }

        $count = 0;
        $notification = new NewsletterCampaignNotification(
            subject: $data['subject'],
            body: $data['body'],
            ctaLabel: $data['cta_label'] ?? null,
            ctaUrl: $data['cta_url'] ?? null,
        );

        $query->chunkById(200, function ($subscribers) use ($notification, &$count): void {
            foreach ($subscribers as $subscriber) {
                Notification::route('mail', $subscriber->email)->notify($notification);
                $count++;
            }
        });

        return $this->ok(
            message: __('messages.newsletter_campaign_queued'),
            data: ['recipients' => $count],
        );
    }
}
