<?php

namespace App\Http\Resources\API\V1\Property;

use App\Http\Resources\API\V1\Attribute\AttributeResource;
use App\Http\Resources\City\CityResource;
use App\Models\Property;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Property $property */
        $property = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $property->id),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($property, 'name')),
            'city' => CityResource::make($this->whenLoaded('city')),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
            'created_at' => $this->whenHas('created_at', fn () => $property->created_at),
        ];
    }
}
