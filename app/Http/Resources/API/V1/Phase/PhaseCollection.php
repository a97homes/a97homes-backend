<?php

namespace App\Http\Resources\API\V1\Phase;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PhaseCollection extends ResourceCollection
{
    public $collects = PhaseResource::class;
}
