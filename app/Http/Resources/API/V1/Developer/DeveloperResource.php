<?php

namespace App\Http\Resources\API\V1\Developer;

use App\Http\Resources\API\V1\Compound\CompoundResource;
use App\Http\Resources\API\V1\ContactMethod\ContactMethodResource;
use App\Http\Resources\API\V1\Faq\FaqResource;
use App\Http\Resources\API\V1\Offer\OfferResource;
use App\Models\Developer;
use App\Models\SubArea;
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
            'whatsapp' => $this->when(
                $developer->relationLoaded('whatsappNumbers') || array_key_exists('whatsapp', $developer->getAttributes()),
                fn () => $this->primaryFormattedContact('whatsappNumbers', $developer->whatsapp),
            ),
            'phone' => $this->when(
                $developer->relationLoaded('phones') || array_key_exists('phone', $developer->getAttributes()),
                fn () => $this->primaryFormattedContact('phones', $developer->phone),
            ),
            'phones' => ContactMethodResource::collection($this->whenLoaded('phones')),
            'whatsapp_numbers' => ContactMethodResource::collection($this->whenLoaded('whatsappNumbers')),
            'is_active' => $this->whenHas('is_active', fn () => $developer->is_active),
            'logo_url' => $developer->logo_url,
            'banner_url' => $developer->banner_url,
            'compounds_count' => $this->whenHas('compounds_count', fn () => $developer->compounds_count),
            'units_count' => $this->whenHas('units_count', fn () => (int) $developer->units_count),
            'sub_areas_count' => $this->whenHas('sub_areas_count', fn () => (int) $developer->sub_areas_count),
            'sub_areas' => $this->when(
                $developer->relationLoaded('subAreas'),
                fn () => $developer->subAreas->unique('id')->values()->map(fn (SubArea $subArea) => [
                    'id' => $subArea->id,
                    'name' => $this->getTranslatableField($subArea, 'name'),
                    'area' => $subArea->relationLoaded('area') && $subArea->area ? [
                        'id' => $subArea->area->id,
                        'name' => $this->getTranslatableField($subArea->area, 'name'),
                    ] : null,
                    'compounds_count' => (int) ($subArea->developer_compounds_count ?? 0),
                    'units_count' => (int) ($subArea->developer_units_count ?? 0),
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

    private function primaryFormattedContact(string $relation, ?string $fallback): ?string
    {
        /** @var Developer $developer */
        $developer = $this->resource;

        if (! $developer->relationLoaded($relation)) {
            return $fallback;
        }

        $contact = $developer->{$relation}->firstWhere('is_primary', true) ?? $developer->{$relation}->first();

        return $contact ? $contact->formatted_number : $fallback;
    }
}
