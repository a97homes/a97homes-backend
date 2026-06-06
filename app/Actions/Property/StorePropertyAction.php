<?php

namespace App\Actions\Property;

use App\Enums\PropertyStatusEnum;
use App\Models\Property;

class StorePropertyAction
{
    public function __construct(public SyncPropertyAttributesAction $syncPropertyAttributesAction) {}

    public function execute(array $data): Property
    {
        $data = collect($data);

        $property = Property::create(
            $data->except(['attributes_ids', 'attribute_values', 'option_ids'])
                ->put('status', $data->get('status') ?? PropertyStatusEnum::ACTIVE->value)
                ->toArray()
        );

        $this->syncPropertyAttributesAction->execute($property, $data);

        return $property;
    }
}
