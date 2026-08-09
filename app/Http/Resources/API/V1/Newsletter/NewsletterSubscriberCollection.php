<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Newsletter;

use App\Http\Resources\BasePaginationResource;

class NewsletterSubscriberCollection extends BasePaginationResource
{
    public $collects = NewsletterSubscriberResource::class;
}
