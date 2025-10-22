<?php

namespace App\Http\Resources\API\V1\Developer;

use App\Http\Resources\API\V1\Project\ProjectCollection;
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
            'projects' => ProjectCollection::make($this->whenLoaded('projects')),
            'created_at' => $this->whenHas('created_at', fn () => $developer->created_at),
        ];
    }
}
