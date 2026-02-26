<?php

namespace App\Http\Resources\API\V1\Property;

use App\Http\Resources\API\V1\Attribute\AttributeOptionResource;
use App\Http\Resources\API\V1\Attribute\AttributeResource;
use App\Http\Resources\API\V1\Project\ProjectResource;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeResource;
use App\Http\Resources\City\CityResource;
use App\Models\Property;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Property $property */
        $property = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $this->id),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($property, 'name')),
            'city' => CityResource::make($this->whenLoaded('city')),
            'property_type' => PropertyTypeResource::make($this->whenLoaded('propertyType')),
            'status' => $this->whenHas('status', fn () => $property->status),
            'address' => $this->whenHas('address', fn () => $property->address),
            'latitude' => $this->whenHas('latitude', fn () => $property->latitude),
            'longitude' => $this->whenHas('longitude', fn () => $property->longitude),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
            'selected_options' => AttributeOptionResource::collection($this->whenLoaded('selectedOptions')),
            'project' => ProjectResource::make($this->whenLoaded('project')),
            'media' => $this->whenLoaded('media', fn () => $property->media->map(fn ($m) => [
                'id' => $m->id,
                'url' => $m->getFullUrl(),
                'type' => $m->mime_type,
            ])),
            'created_at' => $this->whenHas('created_at', fn () => $property->created_at),
        ];
    }
}
