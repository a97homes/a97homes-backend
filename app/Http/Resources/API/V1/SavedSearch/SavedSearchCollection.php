<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\SavedSearch;

use App\Http\Resources\BasePaginationResource;

class SavedSearchCollection extends BasePaginationResource
{
    public $collects = SavedSearchResource::class;
}
