<?php

namespace App\Http\Resources\API\V1\Compound;

use App\Models\Compound;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompoundCompareResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Compound $compound */
        $compound = $this->resource;

        return [
            'id' => $compound->id,
            'name' => $compound->name,
            'developer' => $this->whenLoaded('developer', fn () => [
                'name' => $compound->developer->name,
                'logo' => $compound->developer->logo_url,
            ]),
            'location' => $this->whenLoaded('subArea', fn () => $this->formatLocation($compound)),
            'starting_price' => $compound->properties_min_price !== null ? (int) $compound->properties_min_price : null,
            'resale_price' => $compound->isCompleted()
                ? ($compound->properties_min_resale_price !== null ? (int) $compound->properties_min_resale_price : null)
                : null,
            'discount_percentage' => $this->whenLoaded('activeDiscount', fn () => $compound->activeDiscount?->percentage),
            'unit_types' => $this->whenLoaded('properties', fn () => $compound->properties
                ->pluck('propertyType')
                ->filter()
                ->pluck('name')
                ->unique()
                ->values()
                ->toArray()),
            'completion_status' => $compound->completion_status,
            'delivery_date' => $compound->delivery_date?->toDateString(),
            'total_units' => $this->whenLoaded('properties', fn () => $compound->properties->count()),
            'price_range' => $this->whenLoaded('properties', fn () => [
                'min' => $compound->properties_min_price !== null ? (int) $compound->properties_min_price : null,
                'max' => $compound->properties_max_price !== null ? (int) $compound->properties_max_price : null,
            ]),
        ];
    }

    private function formatLocation(Compound $compound): ?string
    {
        if (! $compound->subArea) {
            return null;
        }

        $parts = [$compound->subArea->getTranslation('name', app()->getLocale())];

        if ($compound->subArea->relationLoaded('area') && $compound->subArea->area) {
            $parts[] = $compound->subArea->area->getTranslation('name', app()->getLocale());
        }

        return implode(', ', $parts);
    }
}
