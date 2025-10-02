<?php

namespace App\Http\Resources\API\V1\Unit;

use App\Http\Resources\BasePaginationResource;
use Illuminate\Http\Request;

class UnitCollection extends BasePaginationResource
{
    public $collects = UnitResource::class;

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
