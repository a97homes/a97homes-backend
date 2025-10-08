<?php

namespace App\Actions\Order;

use App\Models\Order;

class StoreOrderAction
{
    public function execute(): Order
    {
        return Order::create([]);

    }
}
