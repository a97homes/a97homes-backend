<?php

namespace App\Http\Resources\API\V1\Phase;

use App\Http\Resources\BasePaginationResource;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PhaseCollection extends BasePaginationResource
{
    public $collects = PhaseResource::class;
}
