<?php

namespace App\Http\Resources\API\V1\Consultant;

use App\Http\Resources\BasePaginationResource;

class ConsultantCollection extends BasePaginationResource
{
    public $collects = ConsultantResource::class;
}
