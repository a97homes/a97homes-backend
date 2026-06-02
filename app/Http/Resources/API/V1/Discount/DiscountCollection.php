<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Discount;

use App\Http\Resources\BasePaginationResource;

class DiscountCollection extends BasePaginationResource
{
    public $collects = DiscountResource::class;
}
