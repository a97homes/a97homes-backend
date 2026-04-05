<?php

namespace App\Actions\Property;

use App\Models\Property;

class StorePropertyAction
{
    public function execute(): Property
    {
        return Property::create([]);
    }
}
