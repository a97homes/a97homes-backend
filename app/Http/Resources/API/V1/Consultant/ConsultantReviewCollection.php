<?php

namespace App\Http\Resources\API\V1\Consultant;

use App\Http\Resources\BasePaginationResource;

class ConsultantReviewCollection extends BasePaginationResource
{
    public $collects = ConsultantReviewResource::class;
}
