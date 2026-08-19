<?php

namespace App\Actions\Property;

use App\Actions\ContactMethod\SyncContactMethodsAction;
use App\Models\Property;

class UpdatePropertyAction
{
    public function __construct(
        public SyncPropertyAttributesAction $syncPropertyAttributesAction,
        private readonly SyncContactMethodsAction $syncContactMethodsAction,
    ) {}

    public function execute(Property $property, array $data): Property
    {
        $data = collect($data);
        $phones = $data->has('phones') ? $data->get('phones') : null;
        $whatsappNumbers = $data->has('whatsapp_numbers') ? $data->get('whatsapp_numbers') : null;

        $property->update($data->except(['attributes_ids', 'attribute_values', 'option_ids', 'phones', 'whatsapp_numbers'])->toArray());

        $this->syncPropertyAttributesAction->execute($property, $data);
        $this->syncContactMethodsAction->execute($property, $phones, $whatsappNumbers);

        return $property->load(['phones', 'whatsappNumbers']);
    }
}
