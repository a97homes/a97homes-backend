<?php

namespace App\Http\Resources\API\V1\Project;

use App\Http\Resources\API\V1\Developer\DeveloperResource;
use App\Http\Resources\API\V1\Phase\PhaseResource;
use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProjectResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Project $project */
        $project = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $project->id),
            'name' => $this->whenHas('name', fn () => $project->name),
            'developer' => DeveloperResource::make($this->whenLoaded('developer')),
            'phases' => PhaseResource::make($this->whenLoaded('phases')),
            'created_at' => $this->whenHas('created_at', fn () => $project->created_at),
        ];
    }
}
