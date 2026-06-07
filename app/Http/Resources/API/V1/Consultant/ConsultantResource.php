<?php

namespace App\Http\Resources\API\V1\Consultant;

use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultantResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Consultant $consultant */
        $consultant = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $consultant->id),
            'name' => $this->whenHas('name', fn () => $consultant->name),
            'job_title' => $this->whenHas('job_title', fn () => $consultant->job_title),
            'sales_percentage' => $this->whenHas('sales_percentage', fn () => $consultant->sales_percentage),
            'image' => $consultant->getFirstMediaUrl(Consultant::MEDIA_COLLECTION_IMAGE) ?: null,
            'cover_image' => $consultant->getFirstMediaUrl(Consultant::MEDIA_COLLECTION_COVER) ?: null,
            'is_featured' => $this->whenHas('is_featured', fn () => $consultant->is_featured),
            'phones' => ConsultantPhoneResource::collection($this->whenLoaded('phones')),
            'reviews_count' => $this->when($consultant->relationLoaded('reviews') || isset($consultant->reviews_count), fn () => $consultant->reviews_count ?? $consultant->reviews->count()),
            'average_rating' => $this->when($consultant->relationLoaded('reviews') || isset($consultant->average_rating), fn () => $consultant->average_rating ?? round($consultant->reviews->avg('overall_rating'), 2)),
            'rating_breakdown' => $this->when($consultant->relationLoaded('reviews') && $consultant->reviews->isNotEmpty(), fn () => [
                'local_knowledge' => round($consultant->reviews->avg('local_knowledge_rating'), 2),
                'process_expertise' => round($consultant->reviews->avg('process_expertise_rating'), 2),
                'response_speed' => round($consultant->reviews->avg('response_speed_rating'), 2),
                'negotiation_skills' => round($consultant->reviews->avg('negotiation_skills_rating'), 2),
            ]),
            'created_at' => $this->whenHas('created_at', fn () => $consultant->created_at),
        ];
    }
}
