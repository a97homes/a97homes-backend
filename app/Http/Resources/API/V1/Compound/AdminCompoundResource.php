<?php

namespace App\Http\Resources\API\V1\Compound;

use App\Http\Resources\API\V1\Developer\DeveloperResource;
use App\Models\Compound;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCompoundResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Compound $compound */
        $compound = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $compound->id),
            'name' => $this->whenHas('name', fn () => $compound->name),
            'developer' => DeveloperResource::make($this->whenLoaded('developer')),
            'created_at' => $this->whenHas('created_at', fn () => $compound->created_at),
        ];
    }
}
