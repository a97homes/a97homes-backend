<?php

namespace App\Actions\PropertyType;

use App\Models\PropertyType;

class DeletePropertyTypeAction
{
    public function execute(PropertyType $propertyType): bool
    {
        return $propertyType->delete();
    }
}
