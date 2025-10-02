<?php

namespace App\Actions\Property;

use App\Models\Property;

class StorePropertyAction
{
    public function execute(array $data): Property
    {
        return Property::create($data);
    }
}
