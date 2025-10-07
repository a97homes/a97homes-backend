<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Order\OrderResource;
use App\Models\Order;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function store(): JsonResponse
    {
        // TODO: isolate it to action
        $order = Order::create();

        return $this->ok(message: __('messages.order_created_successfully'), data: new OrderResource($order));
    }
}
