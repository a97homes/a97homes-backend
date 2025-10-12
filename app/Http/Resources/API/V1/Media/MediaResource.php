<?php

namespace App\Http\Resources\API\V1\Media;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MediaResource extends JsonResource
{
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
            'id' => $this->whenHas('id', fn () => $this->id),
            'name' => $this->whenHas('file_name', fn () => $property->file_name),
            'url' => $this->whenHas('url', fn () => $this->getUrl()),
        ];
    }
}
