<?php

namespace App\Actions\Property;

use App\Models\Property;

class StorePropertyAction
{
    public function execute(array $data): Property
    {
        $data = collect($data);
        $property = Property::create($data->except(['attributes_ids'])->toArray());
        $property->attributes()->sync($data->get('attributes_ids'));

        return $property;
    }
}
