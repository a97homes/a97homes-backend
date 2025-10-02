<?php

namespace App\Http\Resources\API\V1\Property;

use App\Http\Resources\BasePaginationResource;

class PropertyCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = PropertyResource::class;
}
