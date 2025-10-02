<?php

namespace App\Http\Resources\City;

use App\Http\Resources\BasePaginationResource;

class CityCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = CityResource::class;
}
