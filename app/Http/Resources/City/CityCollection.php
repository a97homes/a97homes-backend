<?php

namespace App\Http\Resources\City;

use App\Http\Resources\BasePaginationResource;
use Illuminate\Http\Request;

class CityCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = CityResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
