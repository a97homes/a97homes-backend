<?php

namespace App\Http\Resources\API\V1\PropertyType;

use App\Http\Resources\BasePaginationResource;
use Illuminate\Http\Request;

class PropertyTypeCollection extends BasePaginationResource
{
    public $collects = PropertyTypeResource::class;

    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
