<?php

namespace App\Http\Resources\API\V1\Compound;

use App\Http\Resources\API\V1\Developer\DeveloperResource;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeResource;
use App\Http\Resources\City\CityResource;
use App\Models\Compound;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompoundResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Compound $compound */
        $compound = $this->resource;

        return [
            'id' => $compound->id,
            'name' => $this->whenHas('name', fn () => $compound->name),
            'description' => $this->whenHas('description', fn () => $compound->description),
            'developer' => DeveloperResource::make($this->whenLoaded('developer')),
            'city' => CityResource::make($this->whenLoaded('city')),
            'starting_price' => $compound->properties_min_price !== null ? (int) $compound->properties_min_price : null,
            'resale_price' => $compound->isCompleted()
                ? ($compound->properties_min_resale_price !== null ? (int) $compound->properties_min_resale_price : null)
                : null,
            'price_range' => [
                'min' => $compound->properties_min_price !== null ? (int) $compound->properties_min_price : null,
                'max' => $compound->properties_max_price !== null ? (int) $compound->properties_max_price : null,
            ],
            'discount_percentage' => $this->whenLoaded('activeDiscount', fn () => $compound->activeDiscount?->percentage),
            'properties_types' => PropertyTypeResource::collection($this->whenLoaded('properties', fn () => $compound->properties
                ->pluck('propertyType')
                ->filter()
                ->unique('id')
                ->values())),
            'total_units' => $this->whenLoaded('properties', fn () => $compound->properties->count()),
            'media' => $this->whenLoaded('media', fn () => $compound->media->map(fn ($m) => [
                'id' => $m->id,
                'url' => $m->getFullUrl(),
                'type' => $m->mime_type,
            ])),
            'is_favorited' => (bool) ($compound->is_favorited ?? $compound->favorites_count ?? false),
        ];
    }
}
