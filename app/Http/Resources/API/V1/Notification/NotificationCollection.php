<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Notification;

use App\Http\Resources\BasePaginationResource;

class NotificationCollection extends BasePaginationResource
{
    public $collects = NotificationResource::class;
}
