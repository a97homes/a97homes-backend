<?php

namespace App\Actions\PropertyType;

use App\Models\PropertyType;

class StorePropertyTypeAction
{
    public function execute(array $data): PropertyType
    {
        $data = collect($data);
        $propertyType = PropertyType::create($data->except(['attributes_ids'])->toArray());
        $propertyType->attributes()->sync($data->get('attributes_ids'));

        return $propertyType;
    }
}
