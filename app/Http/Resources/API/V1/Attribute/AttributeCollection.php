<?php

namespace App\Http\Resources\API\V1\Attribute;

use App\Http\Resources\BasePaginationResource;

class AttributeCollection extends BasePaginationResource
{
    public $collects = AttributeResource::class;
}
