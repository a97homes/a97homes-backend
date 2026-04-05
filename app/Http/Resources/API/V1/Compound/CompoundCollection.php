<?php

namespace App\Http\Resources\API\V1\Compound;

use App\Http\Resources\BasePaginationResource;

class CompoundCollection extends BasePaginationResource
{
    public $collects = CompoundResource::class;
}
