<?php

namespace App\Actions\Property;

use App\Actions\ContactMethod\SyncContactMethodsAction;
use App\Enums\PropertyStatusEnum;
use App\Models\Property;

class StorePropertyAction
{
    public function __construct(
        public SyncPropertyAttributesAction $syncPropertyAttributesAction,
        private readonly SyncContactMethodsAction $syncContactMethodsAction,
    ) {}

    public function execute(array $data): Property
    {
        $data = collect($data);
        $phones = $data->get('phones');
        $whatsappNumbers = $data->get('whatsapp_numbers');

        $property = Property::create(
            $data->except(['attributes_ids', 'attribute_values', 'option_ids', 'phones', 'whatsapp_numbers'])
                ->put('status', $data->get('status') ?? PropertyStatusEnum::ACTIVE->value)
                ->toArray()
        );

        $this->syncPropertyAttributesAction->execute($property, $data);
        $this->syncContactMethodsAction->execute($property, $phones, $whatsappNumbers);

        return $property->load(['phones', 'whatsappNumbers']);
    }
}
