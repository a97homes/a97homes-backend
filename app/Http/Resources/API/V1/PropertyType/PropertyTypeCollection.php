<?php

namespace App\Http\Resources\API\V1\PropertyType;

use App\Http\Resources\BasePaginationResource;

class PropertyTypeCollection extends BasePaginationResource
{
    public $collects = PropertyTypeResource::class;
}
