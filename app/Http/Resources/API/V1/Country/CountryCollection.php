<?php

namespace App\Http\Resources\API\V1\Country;

use App\Http\Resources\BasePaginationResource;

class CountryCollection extends BasePaginationResource
{
    /**
     * The resource that this resource collects.
     *
     * @var string
     */
    public $collects = CountryResource::class;
}
