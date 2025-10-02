<?php

namespace App\Http\Resources\API\V1\Attribute;

use App\Http\Resources\BasePaginationResource;
use Illuminate\Http\Request;

class AttributeCollection extends BasePaginationResource
{
    public $collects = AttributeResource::class;

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
