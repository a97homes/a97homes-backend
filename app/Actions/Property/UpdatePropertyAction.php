<?php

namespace App\Actions\Property;

use App\Enums\PropertyStatusEnum;
use App\Models\Property;

class UpdatePropertyAction
{
    public function execute(Property $property, array $data): Property
    {
        $data = collect($data);
        $data->push('status', PropertyStatusEnum::ACTIVE);
        $property->update($data->except(['attributes_ids'])->toArray());
        $property->attributes()->sync($data->get('attributes_ids'));

        return $property;
    }
}
