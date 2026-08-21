<?php

namespace App\Http\Resources\Area;

use App\Http\Resources\BasePaginationResource;

class AreaCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = AreaResource::class;
}
