<?php

namespace App\Actions\Property;

use App\Models\Property;

class UpdatePropertyStatusAction
{
    public function execute(Property $property, string $status): Property
    {
        $property->update(['status' => $status]);

        return $property;
    }
}
