<?php

namespace App\Http\Resources\API\V1\Attribute;

use App\Models\AttributeOption;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeOptionResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var AttributeOption $option */
        $option = $this->resource;

        return [
            'id' => $option->id,
            'value' => $this->getTranslatableField($option, 'value'),
        ];
    }
}
