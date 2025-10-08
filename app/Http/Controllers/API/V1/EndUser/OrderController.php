<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Actions\Order\StoreOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Order\OrderResource;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function store(StoreOrderAction $storeOrderAction): JsonResponse
    {
        $order = $storeOrderAction->execute();

        return $this->ok(message: __('messages.order_created_successfully'), data: new OrderResource($order));
    }
}
