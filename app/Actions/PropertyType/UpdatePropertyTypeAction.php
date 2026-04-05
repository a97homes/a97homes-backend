<?php

namespace App\Actions\PropertyType;

use App\Models\PropertyType;

class UpdatePropertyTypeAction
{
    public function execute(PropertyType $propertyType, array $data): PropertyType
    {
        $propertyType->update($data);

        return $propertyType;
    }
}
