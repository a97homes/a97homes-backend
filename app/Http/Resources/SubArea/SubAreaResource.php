<?php

declare(strict_types=1);

namespace App\Http\Resources\SubArea;

use App\Http\Resources\Area\AreaResource;
use App\Models\SubArea;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SubAreaResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var SubArea $subArea */
        $subArea = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $subArea->id),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($subArea, 'name')),
            'description' => $this->whenHas('description', fn () => $this->getTranslatableField($subArea, 'description')),
            'latitude' => $this->whenHas('latitude', fn () => $subArea->latitude !== null ? (float) $subArea->latitude : null),
            'longitude' => $this->whenHas('longitude', fn () => $subArea->longitude !== null ? (float) $subArea->longitude : null),
            'area' => AreaResource::make($this->whenLoaded('area')),
            'properties_count' => $this->whenCounted('properties'),
            'units_count' => $this->whenHas('units_count', fn () => (int) $subArea->units_count),
            'compounds_count' => $this->whenHas('compounds_count', fn () => (int) $subArea->compounds_count),
            'image_url' => $this->when(
                $subArea->relationLoaded('media'),
                fn () => $subArea->getFirstMediaUrl(SubArea::MEDIA_COLLECTION_IMAGE) ?: null
            ),
            'created_at' => $this->whenHas('created_at', fn () => $subArea->created_at),
            'updated_at' => $this->whenHas('updated_at', fn () => $subArea->updated_at),
        ];
    }
}
