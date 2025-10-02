<?php

namespace App\Actions\PropertyType;

use App\Models\PropertyType;

class StorePropertyTypeAction
{
    public function execute(array $data): PropertyType
    {
        return PropertyType::create($data);
    }
}
