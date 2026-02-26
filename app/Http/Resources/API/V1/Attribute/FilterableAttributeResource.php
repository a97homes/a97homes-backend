<?php

namespace App\Http\Resources\API\V1\Attribute;

use App\Enums\Attribute\AttributeTypeEnum;
use App\Http\Resources\API\V1\Unit\UnitResource;
use App\Models\Attribute;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FilterableAttributeResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Attribute $attribute */
        $attribute = $this->resource;

        return [
            'id' => $attribute->id,
            'name' => $this->getTranslatableField($attribute, 'name'),
            'type' => $attribute->type,
            'unit' => UnitResource::make($this->whenLoaded('unit')),
            'options' => $attribute->type === AttributeTypeEnum::Select
                ? AttributeOptionResource::collection($this->whenLoaded('activeOptions'))
                : [],
        ];
    }
}
