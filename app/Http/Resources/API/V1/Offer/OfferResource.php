<?php

namespace App\Http\Resources\API\V1\Offer;

use App\Models\Offer;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OfferResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Offer $offer */
        $offer = $this->resource;

        return [
            'id' => $offer->id,
            'installment_years' => $offer->installment_years,
            'down_payment_percentage' => $offer->down_payment_percentage,
            'monthly_payment' => $offer->monthly_payment,
            'description' => $this->whenHas('description', fn () => $this->getTranslatableField($offer, 'description')),
        ];
    }
}
