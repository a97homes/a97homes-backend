<?php

namespace App\Actions\Property;

use App\Models\Property;

class UpdatePropertyAction
{
    public function __construct(public SyncPropertyAttributesAction $syncPropertyAttributesAction) {}

    public function execute(Property $property, array $data): Property
    {
        $data = collect($data);

        $property->update($data->except(['attributes_ids', 'attribute_values', 'option_ids'])->toArray());

        $this->syncPropertyAttributesAction->execute($property, $data);

        return $property;
    }
}
