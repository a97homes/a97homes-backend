<?php

namespace App\Http\Resources\API\V1\Compound;

use App\Http\Resources\BasePaginationResource;

class AdminCompoundCollection extends BasePaginationResource
{
    public $collects = AdminCompoundResource::class;
}
