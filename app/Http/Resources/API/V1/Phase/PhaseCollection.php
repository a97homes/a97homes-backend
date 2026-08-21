<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Phase;

use App\Http\Resources\BasePaginationResource;

class PhaseCollection extends BasePaginationResource
{
    public $collects = PhaseResource::class;
}
