<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\OrderStatusEnum;
use App\Enums\Role\UserRoleEnum;
use App\Enums\SellUnitStatusEnum;
use App\Http\Controllers\Controller;
use App\Models\Compound;
use App\Models\Consultant;
use App\Models\Contact;
use App\Models\Developer;
use App\Models\NewsletterSubscriber;
use App\Models\Order;
use App\Models\Property;
use App\Models\SellUnit;
use App\Models\User\User;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

class DashboardController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_DASHBOARD_SHOW]), only: ['show']),
        ];
    }

    public function show(): JsonResponse
    {
        $now = now();
        $thirtyDaysAgo = $now->copy()->subDays(30);

        $data = [
            'totals' => [
                'users' => User::query()->count(),
                'properties' => Property::query()->count(),
                'compounds' => Compound::query()->count(),
                'developers' => Developer::query()->count(),
                'consultants' => Consultant::query()->count(),
                'orders' => Order::query()->count(),
                'sell_units' => SellUnit::query()->count(),
                'contacts' => Contact::query()->count(),
                'newsletter_subscribers' => NewsletterSubscriber::query()->count(),
            ],
            'orders' => [
                'pending' => Order::query()->where('status', OrderStatusEnum::PENDING->value)->count(),
                'approved' => Order::query()->where('status', OrderStatusEnum::APPROVED->value)->count(),
                'rejected' => Order::query()->where('status', OrderStatusEnum::REJECTED->value)->count(),
            ],
            'sell_units' => [
                'pending' => SellUnit::query()->where('status', SellUnitStatusEnum::PENDING->value)->count(),
                'approved' => SellUnit::query()->where('status', SellUnitStatusEnum::APPROVED->value)->count(),
                'rejected' => SellUnit::query()->where('status', SellUnitStatusEnum::REJECTED->value)->count(),
            ],
            'last_30_days' => [
                'new_users' => User::query()->where('created_at', '>=', $thirtyDaysAgo)->count(),
                'new_orders' => Order::query()->where('created_at', '>=', $thirtyDaysAgo)->count(),
                'new_contacts' => Contact::query()->where('created_at', '>=', $thirtyDaysAgo)->count(),
                'new_sell_units' => SellUnit::query()->where('created_at', '>=', $thirtyDaysAgo)->count(),
                'new_subscribers' => NewsletterSubscriber::query()->where('created_at', '>=', $thirtyDaysAgo)->count(),
            ],
            'recent_orders' => Order::query()
                ->latest()
                ->limit(5)
                ->get(['id', 'name', 'phone', 'status', 'created_at']),
            'recent_contacts' => Contact::query()
                ->latest()
                ->limit(5)
                ->get(['id', 'name', 'email', 'created_at']),
            'recent_sell_units' => SellUnit::query()
                ->latest()
                ->limit(5)
                ->get(['id', 'name', 'phone', 'status', 'created_at']),
        ];

        return $this->ok(data: $data);
    }
}
