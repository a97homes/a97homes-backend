<?php

namespace App\Observers;

use App\Enums\PropertyStatusEnum;
use App\Models\Property;

class PropertyObserver
{
    public function creating(Property $property): void
    {
        $property->status = PropertyStatusEnum::PENDING;
    }
}
