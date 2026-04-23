<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\CompoundReview;

use App\Http\Resources\BasePaginationResource;

class CompoundReviewCollection extends BasePaginationResource
{
    public $collects = CompoundReviewResource::class;
}
