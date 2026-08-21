<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Phase;

use App\Models\Phase;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PhaseResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Phase $phase */
        $phase = $this->resource;

        return [
            'id' => $phase->id,
            'compound_id' => $phase->compound_id,
            'name' => $this->getTranslatableField($phase, 'name'),
            'description' => $this->getTranslatableField($phase, 'description'),
            'delivery_date' => $phase->delivery_date?->toDateString(),
            'completion_status' => $phase->completion_status?->value,
            'sort_order' => $phase->sort_order,
            'created_at' => $phase->created_at,
        ];
    }
}
