<?php

namespace App\Http\Resources\API\V1\Order;

use App\Http\Resources\BasePaginationResource;

class OrderCollection extends BasePaginationResource
{
    public $collects = OrderResource::class;
}
