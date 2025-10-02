<?php

namespace App\Http\Resources\API\V1\Unit;

use App\Models\Unit;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UnitResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Unit $unit */
        $unit = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $unit->id),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($unit, 'name')),
            'symbol' => $this->whenHas('symbol', fn () => $this->getTranslatableField($unit, 'symbol')),
            'type' => $this->whenHas('type', fn () => $unit->type),
            'created_at' => $this->whenHas('created_at', fn () => $unit->created_at),
        ];
    }
}
