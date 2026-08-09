<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Banner;

use App\Http\Resources\BasePaginationResource;

class BannerCollection extends BasePaginationResource
{
    public $collects = BannerResource::class;
}
