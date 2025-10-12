<?php

namespace App\Http\Resources\API\V1\Media;

use App\Http\Resources\BasePaginationResource;

class MediaCollection extends BasePaginationResource
{
    public $collects = MediaResource::class;
}
