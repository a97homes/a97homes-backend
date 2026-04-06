<?php

namespace App\Http\Resources\API\V1\SellUnit;

use App\Http\Resources\API\V1\Compound\AdminCompoundResource;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeResource;
use App\Http\Resources\City\CityResource;
use App\Models\SellUnit;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SellUnitResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var SellUnit $sellUnit */
        $sellUnit = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $sellUnit->id),
            'name' => $this->whenHas('name', fn () => $sellUnit->name),
            'phone' => $this->whenHas('phone', fn () => $sellUnit->phone),
            'status' => $this->whenHas('status', fn () => $sellUnit->status),
            'notes' => $this->whenHas('notes', fn () => $sellUnit->notes),
            'city' => CityResource::make($this->whenLoaded('city')),
            'propertyType' => PropertyTypeResource::make($this->whenLoaded('propertyType')),
            'compound' => AdminCompoundResource::make($this->whenLoaded('compound')),
            'created_at' => $this->whenHas('created_at', fn () => $sellUnit->created_at),
        ];
    }
}
