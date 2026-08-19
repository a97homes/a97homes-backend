<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Actions\Order\StoreOrderAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Order\StoreOrderRequest;
use App\Http\Resources\API\V1\Order\OrderResource;
use Illuminate\Http\JsonResponse;

class OrderController extends Controller
{
    public function store(StoreOrderRequest $request, StoreOrderAction $action): JsonResponse
    {
        $order = $action->execute($request->validated());

        return $this->ok(message: __('messages.order_created_successfully'), data: new OrderResource($order->load('propertyType:name,id', 'subArea:name,id')));
    }
}
