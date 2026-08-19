<?php

namespace App\Http\Resources\API\V1\Compound;

use App\Http\Resources\API\V1\ContactMethod\ContactMethodResource;
use App\Http\Resources\API\V1\Developer\DeveloperResource;
use App\Http\Resources\API\V1\Offer\OfferResource;
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
            'phones' => ContactMethodResource::collection($this->whenLoaded('phones')),
            'whatsapp_numbers' => ContactMethodResource::collection($this->whenLoaded('whatsappNumbers')),
            'developer' => DeveloperResource::make($this->whenLoaded('developer')),
            'delivery_date' => $this->whenHas('delivery_date', fn () => $compound->delivery_date?->toDateString()),
            'completion_status' => $this->whenHas('completion_status', fn () => $compound->completion_status),
            'active_offers' => OfferResource::collection($this->whenLoaded('activeOffers')),
            'active_discount' => $this->whenLoaded('activeDiscount', fn () => $compound->activeDiscount ? [
                'id' => $compound->activeDiscount->id,
                'percentage' => (float) $compound->activeDiscount->percentage,
                'start_date' => $compound->activeDiscount->start_date->toDateString(),
                'end_date' => $compound->activeDiscount->end_date->toDateString(),
            ] : null),
            'created_at' => $this->whenHas('created_at', fn () => $compound->created_at),
        ];
    }
}
