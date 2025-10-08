<?php

namespace App\Http\Resources\API\V1\Contact;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactCollection extends ResourceCollection
{
    public $collects = ContactResource::class;
}
