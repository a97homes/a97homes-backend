<?php

namespace App\Actions\Order;

use App\Models\Order;

class StoreOrderAction
{
    public function execute(array $data): Order
    {
        return Order::create($data);

    }
}
