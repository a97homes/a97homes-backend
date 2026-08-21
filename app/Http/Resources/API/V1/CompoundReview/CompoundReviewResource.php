<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\CompoundReview;

use App\Models\CompoundReview;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompoundReviewResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var CompoundReview $review */
        $review = $this->resource;

        return [
            'id' => $review->id,
            'title' => $review->title,
            'comment' => $review->comment,
            'overall_rating' => $review->overall_rating,
            'location_rating' => $review->location_rating,
            'amenities_rating' => $review->amenities_rating,
            'value_for_money_rating' => $review->value_for_money_rating,
            'developer_reputation_rating' => $review->developer_reputation_rating,
            'user' => $this->whenLoaded('user', fn () => [
                'id' => $review->user->id,
                'name' => $review->user->name,
                'avatar_url' => $review->user->getFirstMediaUrl(\App\Models\User\User::MEDIA_COLLECTION_AVATAR) ?: null,
            ]),
            'created_at' => $review->created_at,
        ];
    }
}
