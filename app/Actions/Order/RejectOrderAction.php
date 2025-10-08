<?php

namespace App\Actions\Order;

use App\Enums\OrderStatusEnum;
use App\Models\Order;

class RejectOrderAction
{
    public function execute(Order $order): Order
    {
        $order->update(['status' => OrderStatusEnum::REJECTED]);

        return $order;
    }
}
