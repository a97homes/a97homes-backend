<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Admin\CompoundReview;

use App\Http\Resources\BasePaginationResource;

class AdminCompoundReviewCollection extends BasePaginationResource
{
    public $collects = AdminCompoundReviewResource::class;
}
