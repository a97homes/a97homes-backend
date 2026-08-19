<?php

namespace App\Http\Resources\API\V1\Property;

use App\Http\Resources\API\V1\Compound\AdminCompoundResource;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeResource;
use App\Http\Resources\SubArea\SubAreaResource;
use App\Models\Property;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PropertyMapResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Lightweight payload for map markers using the property's own coordinates.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Property $property */
        $property = $this->resource;

        return [
            'id' => $property->id,
            'name' => $this->getTranslatableField($property, 'name'),
            'latitude' => $property->latitude !== null ? (float) $property->latitude : null,
            'longitude' => $property->longitude !== null ? (float) $property->longitude : null,
            'price' => $property->price !== null ? (int) $property->price : null,
            'status' => $property->status,
            'sale_type' => $property->sale_type,
            'property_type' => PropertyTypeResource::make($this->whenLoaded('propertyType')),
            'sub_area' => SubAreaResource::make($this->whenLoaded('subArea')),
            'compound' => AdminCompoundResource::make($this->whenLoaded('compound')),
            'thumbnail' => $this->whenLoaded('media', fn () => optional($property->media
                ->firstWhere('collection_name', Property::MEDIA_COLLECTION_FILE))?->getFullUrl()),
        ];
    }
}
