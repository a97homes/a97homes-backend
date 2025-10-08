<?php

namespace App\Observers;

use App\Enums\OrderStatusEnum;
use App\Models\Order;

class OrderObserver
{
    public function creating(Order $Order): void
    {
        if (authCheck()) {
            $Order->user_id = authUserId();
        }
        $Order->status = OrderStatusEnum::PENDING;
    }
}
