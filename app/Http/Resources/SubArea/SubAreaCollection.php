<?php

namespace App\Http\Resources\SubArea;

use App\Http\Resources\BasePaginationResource;

class SubAreaCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = SubAreaResource::class;
}
