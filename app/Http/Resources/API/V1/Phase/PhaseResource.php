<?php

namespace App\Http\Resources\API\V1\Phase;

use App\Http\Resources\API\V1\Project\ProjectResource;
use App\Models\Phase;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhaseResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Phase $phase */
        $phase = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $phase->id),
            'name' => $this->whenHas('name', fn () => $phase->name),
            'project' => ProjectResource::make($this->whenLoaded('project')),
            'created_at' => $this->whenHas('created_at', fn () => $phase->created_at),
        ];
    }
}
