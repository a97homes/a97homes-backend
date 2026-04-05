<?php

namespace App\Http\Resources\API\V1\Contact;

use App\Http\Resources\BasePaginationResource;

class ContactCollection extends BasePaginationResource
{
    public $collects = ContactResource::class;
}
