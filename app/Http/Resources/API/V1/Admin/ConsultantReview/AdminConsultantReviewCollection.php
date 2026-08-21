<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Admin\ConsultantReview;

use App\Http\Resources\BasePaginationResource;

class AdminConsultantReviewCollection extends BasePaginationResource
{
    public $collects = AdminConsultantReviewResource::class;
}
