<?php

namespace App\Actions\Order;

use App\Enums\OrderStatusEnum;
use App\Models\Order;

class ApproveOrderAction
{
    public function execute(Order $order): Order
    {
        $order->update(['status' => OrderStatusEnum::APPROVED]);

        return $order;
    }
}
