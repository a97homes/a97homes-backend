<?php

namespace App\Http\Resources\Area;

use App\Http\Resources\API\V1\Country\CountryResource;
use App\Models\Area;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AreaResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Area $area */
        $area = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $area->id),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($area, 'name')),
            'about' => $this->whenHas('about', fn () => $this->getTranslatableField($area, 'about')),
            'country' => CountryResource::make($this->whenLoaded('country')),
            'sub_areas_count' => $this->whenCounted('subAreas'),
            'cover_url' => $this->when(
                $area->relationLoaded('media'),
                fn () => $area->cover_url,
            ),
            'logo_url' => $this->when(
                $area->relationLoaded('media'),
                fn () => $area->logo_url,
            ),
            'created_at' => $this->whenHas('created_at', fn () => $area->created_at),
            'updated_at' => $this->whenHas('updated_at', fn () => $area->updated_at),
        ];
    }
}
