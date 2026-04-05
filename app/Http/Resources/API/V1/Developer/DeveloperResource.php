<?php

namespace App\Http\Resources\API\V1\Developer;

use App\Http\Resources\API\V1\Compound\CompoundResource;
use App\Models\Developer;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeveloperResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Developer $developer */
        $developer = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $developer->id),
            'name' => $this->whenHas('name', fn () => $developer->name),
            'about' => $this->whenHas('about', fn () => $developer->about),
            'logo_url' => $developer->logo_url,
            'compounds_count' => $this->whenHas('compounds_count', fn () => $developer->compounds_count),
            'compounds' => CompoundResource::collection($this->whenLoaded('compounds')),
            'created_at' => $this->whenHas('created_at', fn () => $developer->created_at),
        ];
    }
}
