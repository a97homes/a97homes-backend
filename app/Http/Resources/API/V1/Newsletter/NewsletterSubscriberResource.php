<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Newsletter;

use App\Models\NewsletterSubscriber;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class NewsletterSubscriberResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var NewsletterSubscriber $subscriber */
        $subscriber = $this->resource;

        return [
            'id' => $subscriber->id,
            'email' => $subscriber->email,
            'locale' => $subscriber->locale,
            'status' => $subscriber->status?->value,
            'source' => $subscriber->source,
            'subscribed_at' => $subscriber->subscribed_at,
            'unsubscribed_at' => $subscriber->unsubscribed_at,
            'created_at' => $subscriber->created_at,
        ];
    }
}
