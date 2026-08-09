<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\SellUnit;

use App\Http\Resources\BasePaginationResource;

class SellUnitCollection extends BasePaginationResource
{
    public $collects = SellUnitResource::class;
}
