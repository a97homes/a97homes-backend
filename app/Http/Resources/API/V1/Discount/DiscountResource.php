<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Discount;

use App\Models\Discount;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DiscountResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Discount $discount */
        $discount = $this->resource;

        return [
            'id' => $discount->id,
            'compound_id' => $discount->compound_id,
            'percentage' => $discount->percentage,
            'start_date' => $discount->start_date?->toDateString(),
            'end_date' => $discount->end_date?->toDateString(),
            'is_active' => $discount->is_active,
            'compound' => $this->whenLoaded('compound', fn () => [
                'id' => $discount->compound->id,
                'name' => $discount->compound->name,
            ]),
            'created_at' => $discount->created_at,
        ];
    }
}
