<?php

namespace App\Http\Resources\State;

use App\Http\Resources\API\V1\Country\CountryResource;
use App\Models\State;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class StateResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var State $state */
        $state = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $state->id),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($state, 'name')),
            'country' => CountryResource::make($this->whenLoaded('country')),
            'created_at' => $this->whenHas('created_at', fn () => $state->created_at),
        ];
    }
}
