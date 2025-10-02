<?php

namespace App\Actions\Property;

use App\Models\Property;

class UpdatePropertyAction
{
    public function execute(Property $property, array $data): Property
    {
        $property->update($data);

        return $property;
    }
}
