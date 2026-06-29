<?php

namespace App\Http\Resources\API\V1\Developer;

use App\Http\Resources\API\V1\Compound\CompoundResource;
use App\Http\Resources\API\V1\Faq\FaqResource;
use App\Http\Resources\API\V1\Offer\OfferResource;
use App\Models\City;
use App\Models\Developer;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeveloperResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Developer $developer */
        $developer = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $developer->id),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($developer, 'name')),
            'about' => $this->whenHas('about', fn () => $this->getTranslatableField($developer, 'about')),
            'whatsapp' => $this->whenHas('whatsapp', fn () => $developer->whatsapp),
            'phone' => $this->whenHas('phone', fn () => $developer->phone),
            'is_active' => $this->whenHas('is_active', fn () => $developer->is_active),
            'logo_url' => $developer->logo_url,
            'banner_url' => $developer->banner_url,
            'compounds_count' => $this->whenHas('compounds_count', fn () => $developer->compounds_count),
            'units_count' => $this->whenHas('units_count', fn () => (int) $developer->units_count),
            'areas_count' => $this->whenHas('areas_count', fn () => (int) $developer->areas_count),
            'areas' => $this->when(
                $developer->relationLoaded('areas'),
                fn () => $developer->areas->map(fn (City $city) => [
                    'id' => $city->id,
                    'name' => $this->getTranslatableField($city, 'name'),
                ]),
            ),
            'offers' => $this->when(
                $developer->relationLoaded('offers') || $developer->relationLoaded('activeOffers'),
                fn () => OfferResource::collection(
                    $developer->relationLoaded('offers') ? $developer->offers : $developer->activeOffers,
                ),
            ),
            'faqs' => $this->when(
                $developer->relationLoaded('faqs') || $developer->relationLoaded('activeFaqs'),
                fn () => FaqResource::collection(
                    $developer->relationLoaded('faqs') ? $developer->faqs : $developer->activeFaqs,
                ),
            ),
            'compounds' => CompoundResource::collection($this->whenLoaded('compounds')),
            'created_at' => $this->whenHas('created_at', fn () => $developer->created_at),
        ];
    }
}
