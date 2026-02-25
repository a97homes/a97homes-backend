<?php

namespace App\Http\Resources\API\V1\Developer;

use App\Http\Resources\BasePaginationResource;

class DeveloperCollection extends BasePaginationResource
{
    public $collects = DeveloperResource::class;
}
