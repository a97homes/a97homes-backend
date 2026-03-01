<?php

namespace App\Http\Resources\API\V1\Property;

use App\Http\Resources\API\V1\Attribute\AttributeOptionResource;
use App\Http\Resources\API\V1\Attribute\AttributeResource;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeResource;
use App\Http\Resources\City\CityResource;
use App\Models\Property;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyCompareResource extends JsonResource
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
            'id' => $property->id,
            'name' => $this->getTranslatableField($property, 'name'),
            'property_type' => PropertyTypeResource::make($this->whenLoaded('propertyType')),
            'compound' => $this->whenLoaded('compound', fn () => [
                'id' => $property->compound->id,
                'name' => $property->compound->name,
                'developer' => $property->compound->relationLoaded('developer') ? [
                    'name' => $property->compound->developer->name,
                    'logo' => $property->compound->developer->logo_url,
                ] : null,
                'delivery_date' => $property->compound->delivery_date?->toDateString(),
                'completion_status' => $property->compound->completion_status,
            ]),
            'location' => CityResource::make($this->whenLoaded('city')),
            'price' => $property->price,
            'resale_price' => $property->resale_price,
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),
            'selected_options' => AttributeOptionResource::collection($this->whenLoaded('selectedOptions')),
            'media' => $this->whenLoaded('media', fn () => $property->media->map(fn ($m) => [
                'id' => $m->id,
                'url' => $m->getFullUrl(),
                'type' => $m->mime_type,
            ])),
        ];
    }
}
