<?php

namespace App\Http\Resources\City;

use App\Http\Resources\State\StateResource;
use App\Models\City;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CityResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {      /** @var City $city */
        $city = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $city->id),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($city, 'name')),
            'state' => StateResource::make($this->whenLoaded('state')),
            'created_at' => $this->whenHas('created_at', fn () => $city->created_at),
        ];
    }
}
