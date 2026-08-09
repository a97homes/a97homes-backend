<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Page;

use App\Http\Resources\BasePaginationResource;

class PageCollection extends BasePaginationResource
{
    public $collects = PageResource::class;
}
