<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Order\ApproveOrderAction;
use App\Actions\Order\RejectOrderAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Order\OrderCollection;
use App\Http\Resources\API\V1\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.orders.index']), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.orders.show']), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.orders.approve']), only: ['approve']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.orders.reject']), only: ['reject']),
        ];
    }

    public function index(): JsonResponse
    {
        $orders = QueryBuilder::for(Order::class)
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('status'),
            ])
            ->macroPaginate();

        return $this->ok(data: new OrderCollection($orders));
    }

    public function show(Order $order): JsonResponse
    {
        return $this->ok(data: new OrderResource($order->load('user:id,name')));
    }

    public function approve(Order $order, ApproveOrderAction $action): JsonResponse
    {
        $action->execute($order);

        return $this->ok(message: __('messages.order_accepted_successfully'));
    }

    public function reject(Order $order, RejectOrderAction $action): JsonResponse
    {
        $action->execute($order);

        return $this->ok(message: __('messages.order_rejected_successfully'));
    }
}
