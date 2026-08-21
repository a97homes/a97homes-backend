<?php

namespace App\Http\Resources\API\V1\Compound;

use App\Http\Resources\API\V1\Developer\DeveloperResource;
use App\Http\Resources\SubArea\SubAreaResource;
use App\Models\Compound;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompoundMapResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Lightweight payload for map markers — a compound is positioned at its
     * subArea's coordinates since compounds have no own latitude/longitude.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Compound $compound */
        $compound = $this->resource;
        $subArea = $compound->subArea;

        return [
            'id' => $compound->id,
            'name' => $compound->name,
            'completion_status' => $compound->completion_status,
            'latitude' => $subArea?->latitude !== null ? (float) $subArea->latitude : null,
            'longitude' => $subArea?->longitude !== null ? (float) $subArea->longitude : null,
            'starting_price' => $compound->properties_min_price !== null ? (int) $compound->properties_min_price : null,
            'sub_area' => SubAreaResource::make($this->whenLoaded('subArea')),
            'developer' => $this->when(
                $compound->relationLoaded('developer') && optional($compound->developer)->is_active,
                fn () => DeveloperResource::make($compound->developer),
            ),
            'thumbnail' => $this->whenLoaded('media', fn () => optional($compound->media
                ->firstWhere('collection_name', Compound::MEDIA_COLLECTION_IMAGE))?->getFullUrl()),
        ];
    }
}
