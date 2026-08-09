<?php

namespace App\Actions\Property;

use App\Models\AttributeOption;
use App\Models\Property;
use Illuminate\Support\Collection;

class SyncPropertyAttributesAction
{
    /**
     * Sync property attributes (with pivot values) and selected attribute options.
     *
     * @param  Collection<string, mixed>  $data
     */
    public function execute(Property $property, Collection $data): void
    {
        $values = collect($data->get('attribute_values', []));

        $syncPayload = collect($data->get('attributes_ids', []))
            ->mapWithKeys(fn ($attributeId) => [
                (int) $attributeId => ['value' => $values->get($attributeId, $values->get((string) $attributeId))],
            ])
            ->toArray();

        $property->attributes()->sync($syncPayload);

        if (! $data->has('option_ids')) {
            return;
        }

        $optionsPayload = AttributeOption::query()
            ->whereIn('id', $data->get('option_ids', []))
            ->get(['id', 'attribute_id'])
            ->mapWithKeys(fn (AttributeOption $option) => [
                $option->id => ['attribute_id' => $option->attribute_id],
            ])
            ->toArray();

        $property->selectedOptions()->sync($optionsPayload);
    }
}
