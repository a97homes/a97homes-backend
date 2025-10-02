<?php

namespace App\Http\Resources\API\V1\Unit;

use App\Http\Resources\BasePaginationResource;

class UnitCollection extends BasePaginationResource
{
    public $collects = UnitResource::class;
}
