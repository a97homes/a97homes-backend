<?php

namespace App\Http\Resources\API\V1\Consultant;

use App\Models\ConsultantReview;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultantReviewResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ConsultantReview $review */
        $review = $this->resource;

        return [
            'id' => $review->id,
            'reviewer' => $this->when($review->relationLoaded('user') && $review->user, fn () => [
                'id' => $review->user->id,
                'name' => $review->user->name,
                'image' => $review->user->getFirstMediaUrl(\App\Models\User\User::MEDIA_COLLECTION_AVATAR) ?: null,
            ]),
            'title' => $review->title,
            'comment' => $review->comment,
            'overall_rating' => $review->overall_rating,
            'local_knowledge_rating' => $review->local_knowledge_rating,
            'process_expertise_rating' => $review->process_expertise_rating,
            'response_speed_rating' => $review->response_speed_rating,
            'negotiation_skills_rating' => $review->negotiation_skills_rating,
            'created_at' => $review->created_at,
        ];
    }
}
