<?php

namespace App\Http\Resources\API\V1\Property;

use App\Http\Resources\API\V1\Attribute\AttributeOptionResource;
use App\Http\Resources\API\V1\Attribute\AttributeResource;
use App\Http\Resources\API\V1\Compound\CompoundResource;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeResource;
use App\Http\Resources\City\CityResource;
use App\Models\Compound;
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
            'price' => $this->whenHas('price', fn () => $property->price),
            'resale_price' => $this->whenHas('resale_price', fn () => $property->resale_price),
            'sale_type' => $this->whenHas('sale_type', fn () => $property->sale_type),
            'latitude' => $this->whenHas('latitude', fn () => $property->latitude),
            'longitude' => $this->whenHas('longitude', fn () => $property->longitude),
            'attributes' => AttributeResource::collection($this->whenLoaded('attributes')),

            'selected_options' => AttributeOptionResource::collection($this->whenLoaded('selectedOptions')),
            'compound' => CompoundResource::make($this->whenLoaded('compound')),
            'delivery_date' => $this->whenLoaded('compound', fn () => $property->compound?->delivery_date?->toDateString()),
            'floor_plan' => $this->whenLoaded('media', fn () => $property->media
                ->where('collection_name', Property::MEDIA_COLLECTION_FLOOR_PLAN)
                ->map(fn ($m) => [
                    'id' => $m->id,
                    'url' => $m->getFullUrl(),
                    'type' => $m->mime_type,
                ])->values()),
            'project_plan' => $this->when(
                $property->compound?->relationLoaded('media') ?? false,
                fn () => $property->compound->media
                    ->where('collection_name', Compound::MEDIA_COLLECTION_PROJECT_PLAN)
                    ->map(fn ($m) => [
                        'id' => $m->id,
                        'url' => $m->getFullUrl(),
                        'type' => $m->mime_type,
                    ])->values()
            ),
            'media' => $this->whenLoaded('media', fn () => $property->media
                ->where('collection_name', Property::MEDIA_COLLECTION_FILE)
                ->map(fn ($m) => [
                    'id' => $m->id,
                    'url' => $m->getFullUrl(),
                    'type' => $m->mime_type,
                ])->values()),
            'is_favorited' => (bool) ($property->is_favorited ?? $property->property_favorites_count ?? false),
            'created_at' => $this->whenHas('created_at', fn () => $property->created_at),
        ];
    }
}
