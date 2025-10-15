<?php

namespace App\Http\Resources\API\V1\Social;

use App\Http\Resources\BasePaginationResource;

class SocialCollection extends BasePaginationResource
{
    public $collects = SocialResource::class;
}
