<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Newsletter\NewsletterSubscriberCollection;
use App\Http\Resources\API\V1\Newsletter\NewsletterSubscriberResource;
use App\Models\NewsletterSubscriber;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class NewsletterSubscriberController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_NEWSLETTER_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_NEWSLETTER_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_NEWSLETTER_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $subscribers = QueryBuilder::for(NewsletterSubscriber::class)
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('locale'),
                AllowedFilter::partial('email'),
                AllowedFilter::exact('source'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('email'),
                AllowedSort::field('subscribed_at'),
                AllowedSort::field('unsubscribed_at'),
                AllowedSort::field('created_at'),
            ])
            ->macroPaginate();

        return $this->ok(data: new NewsletterSubscriberCollection($subscribers));
    }

    public function show(NewsletterSubscriber $newsletterSubscriber): JsonResponse
    {
        return $this->ok(data: NewsletterSubscriberResource::make($newsletterSubscriber));
    }

    public function destroy(NewsletterSubscriber $newsletterSubscriber): JsonResponse
    {
        $newsletterSubscriber->delete();

        return $this->ok(message: __('messages.newsletter_subscriber_deleted'));
    }
}
