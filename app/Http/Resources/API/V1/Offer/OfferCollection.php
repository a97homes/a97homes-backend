<?php

namespace App\Http\Resources\API\V1\Offer;

use App\Http\Resources\BasePaginationResource;

class OfferCollection extends BasePaginationResource
{
    public $collects = OfferResource::class;
}
