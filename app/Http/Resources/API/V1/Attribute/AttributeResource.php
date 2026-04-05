<?php

namespace App\Http\Resources\API\V1\Attribute;

use App\Http\Resources\API\V1\Unit\UnitResource;
use App\Models\Attribute;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AttributeResource extends JsonResource
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
            'id' => $this->whenHas('id', fn () => $attribute->id),
            'slug' => $this->whenHas('slug', fn () => $attribute->slug),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($attribute, 'name')),
            'type' => $this->whenHas('type', fn () => $attribute->type),
            'value' => $this->when($attribute->pivot?->value !== null, fn () => $attribute->pivot?->value),
            'unit' => UnitResource::make($this->whenLoaded('unit')),
            'created_at' => $this->whenHas('created_at', fn () => $attribute->created_at),
        ];
    }
}
