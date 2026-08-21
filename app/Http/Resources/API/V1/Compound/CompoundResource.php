<?php

namespace App\Http\Resources\API\V1\Compound;

use App\Http\Resources\API\V1\ContactMethod\ContactMethodResource;
use App\Http\Resources\API\V1\Developer\DeveloperResource;
use App\Http\Resources\API\V1\Offer\OfferResource;
use App\Http\Resources\API\V1\PaymentPlan\PaymentPlanResource;
use App\Http\Resources\API\V1\Property\PropertyResource;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeResource;
use App\Http\Resources\SubArea\SubAreaResource;
use App\Models\Compound;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompoundResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Compound $compound */
        $compound = $this->resource;

        return [
            'id' => $compound->id,
            'name' => $this->whenHas('name', fn () => $compound->name),
            'description' => $this->whenHas('description', fn () => $compound->description),
            'phones' => ContactMethodResource::collection($this->whenLoaded('phones')),
            'whatsapp_numbers' => ContactMethodResource::collection($this->whenLoaded('whatsappNumbers')),
            'developer' => $this->when(
                $compound->relationLoaded('developer') && optional($compound->developer)->is_active,
                fn () => DeveloperResource::make($compound->developer),
            ),
            'sub_area' => SubAreaResource::make($this->whenLoaded('subArea')),
            'starting_price' => $this->whenHas('properties_min_price', fn () => $compound->properties_min_price !== null ? (int) $compound->properties_min_price : null),
            'resale_price' => $this->whenHas('properties_min_resale_price', fn () => $compound->isCompleted()
                ? ($compound->properties_min_resale_price !== null ? (int) $compound->properties_min_resale_price : null)
                : null),
            'price_range' => $this->whenHas('properties_min_price', fn () => [
                'min' => $compound->properties_min_price !== null ? (int) $compound->properties_min_price : null,
                'max' => $compound->properties_max_price !== null ? (int) $compound->properties_max_price : null,
            ]),
            'discount_percentage' => $this->whenLoaded('activeDiscount', fn () => $compound->activeDiscount?->percentage),
            'properties_types' => PropertyTypeResource::collection($this->whenLoaded('properties', fn () => $compound->properties
                ->pluck('propertyType')
                ->filter()
                ->unique('id')
                ->values())),
            'total_units' => $this->whenHas('properties_count', fn () => $compound->properties_count),
            'properties' => PropertyResource::collection($this->whenLoaded('properties')),
            'payment_plans' => PaymentPlanResource::collection($this->whenLoaded('activePaymentPlans')),
            'media' => $this->whenLoaded('media', fn () => $compound->media
                ->where('collection_name', Compound::MEDIA_COLLECTION_IMAGE)
                ->map(fn ($m) => [
                    'id' => $m->id,
                    'url' => $m->getFullUrl(),
                    'type' => $m->mime_type,
                ])->values()),
            'project_plan' => $this->whenLoaded('media', fn () => $compound->media
                ->where('collection_name', Compound::MEDIA_COLLECTION_PROJECT_PLAN)
                ->map(fn ($m) => [
                    'id' => $m->id,
                    'url' => $m->getFullUrl(),
                    'type' => $m->mime_type,
                ])->values()),
            'floor_plan' => $this->whenLoaded('media', fn () => $compound->media
                ->where('collection_name', Compound::MEDIA_COLLECTION_FLOOR_PLAN)
                ->map(fn ($m) => [
                    'id' => $m->id,
                    'url' => $m->getFullUrl(),
                    'type' => $m->mime_type,
                ])->values()),
            'offers' => OfferResource::collection($this->whenLoaded('activeOffers')),
            'is_favorited' => (bool) ($compound->is_favorited ?? $compound->favorites_count ?? false),
            'reviews_count' => $this->whenHas('reviews_count', fn () => (int) $compound->reviews_count),
            'average_rating' => $this->whenHas('reviews_avg_overall_rating', fn () => $compound->reviews_avg_overall_rating !== null
                ? round((float) $compound->reviews_avg_overall_rating, 2)
                : null),
        ];
    }
}
