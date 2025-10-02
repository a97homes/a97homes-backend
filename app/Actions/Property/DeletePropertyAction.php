<?php

namespace App\Actions\Property;

use App\Models\Property;

class DeletePropertyAction
{
    public function execute(Property $property): bool
    {
        return $property->delete();
    }
}
