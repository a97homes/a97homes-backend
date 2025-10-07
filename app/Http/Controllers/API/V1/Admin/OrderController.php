<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\OrderStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Order\OrderCollection;
use App\Models\Order;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class OrderController extends Controller
{
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
    // TODO:: show order

    public function approve(Order $order): JsonResponse
    {
        // TODO: isolate to actions
        $order->update(['status' => OrderStatusEnum::APPROVED]);

        return $this->ok(message: __('messages.order_accepted_successfully'));
    }

    public function reject(Order $order): JsonResponse
    {
        // TODO: isolate to actions
        $order->update(['status' => OrderStatusEnum::REJECTED]);

        return $this->ok(message: __('messages.order_rejected_successfully'));
    }
}
