<?php

namespace App\Http\Resources\API\V1\PropertyType;

use App\Models\PropertyType;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyTypeResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray($request): array
    {
        /** @var PropertyType $propertyType */
        $propertyType = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $propertyType->id),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($propertyType, 'name')),
            'created_at' => $this->whenHas('created_at', fn () => $propertyType->created_at),
        ];
    }
}
