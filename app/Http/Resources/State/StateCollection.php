<?php

namespace App\Http\Resources\State;

use App\Http\Resources\BasePaginationResource;
use Illuminate\Http\Request;

class StateCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = StateResource::class;

    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
