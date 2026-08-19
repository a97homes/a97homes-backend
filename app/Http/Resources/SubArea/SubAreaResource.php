<?php

declare(strict_types=1);

namespace App\Http\Resources\City;

use App\Http\Resources\State\StateResource;
use App\Models\City;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var City $city */
        $city = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $city->id),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($city, 'name')),
            'description' => $this->whenHas('description', fn () => $this->getTranslatableField($city, 'description')),
            'latitude' => $this->whenHas('latitude', fn () => $city->latitude !== null ? (float) $city->latitude : null),
            'longitude' => $this->whenHas('longitude', fn () => $city->longitude !== null ? (float) $city->longitude : null),
            'state' => StateResource::make($this->whenLoaded('state')),
            'properties_count' => $this->whenCounted('properties'),
            'units_count' => $this->whenHas('units_count', fn () => (int) $city->units_count),
            'compounds_count' => $this->whenHas('compounds_count', fn () => (int) $city->compounds_count),
            'image_url' => $this->when(
                $city->relationLoaded('media'),
                fn () => $city->getFirstMediaUrl(City::MEDIA_COLLECTION_IMAGE) ?: null
            ),
            'created_at' => $this->whenHas('created_at', fn () => $city->created_at),
        ];
    }
}
