<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Admin\ConsultantReview;

use App\Models\ConsultantReview;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminConsultantReviewResource extends JsonResource
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
            'title' => $review->title,
            'comment' => $review->comment,
            'overall_rating' => $review->overall_rating,
            'local_knowledge_rating' => $review->local_knowledge_rating,
            'process_expertise_rating' => $review->process_expertise_rating,
            'response_speed_rating' => $review->response_speed_rating,
            'negotiation_skills_rating' => $review->negotiation_skills_rating,
            'consultant' => $this->whenLoaded('consultant', fn () => [
                'id' => $review->consultant->id,
                'name' => $review->consultant->name,
            ]),
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $review->user->id,
                'name' => $review->user->name,
                'email' => $review->user->email,
                'avatar_url' => $review->user->getFirstMediaUrl(User::MEDIA_COLLECTION_AVATAR) ?: null,
            ]),
            'created_at' => $review->created_at,
        ];
    }
}
