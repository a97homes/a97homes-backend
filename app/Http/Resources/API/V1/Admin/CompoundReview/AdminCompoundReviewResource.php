<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Admin\CompoundReview;

use App\Models\CompoundReview;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class AdminCompoundReviewResource extends JsonResource
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
            'compound' => $this->whenLoaded('compound', fn () => [
                'id' => $review->compound->id,
                'name' => $review->compound->name,
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
