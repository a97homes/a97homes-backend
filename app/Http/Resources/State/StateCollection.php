<?php

namespace App\Http\Resources\State;

use App\Http\Resources\BasePaginationResource;

class StateCollection extends BasePaginationResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public $collects = StateResource::class;
}
