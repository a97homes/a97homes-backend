<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\EndUser;

use App\Enums\NewsletterStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Newsletter\SubscribeRequest;
use App\Http\Requests\API\V1\EndUser\Newsletter\UnsubscribeRequest;
use App\Models\NewsletterSubscriber;
use Illuminate\Http\JsonResponse;

class NewsletterController extends Controller
{
    /**
     * Subscribe an email to the newsletter. Idempotent — resubscribing
     * an already-active email is a no-op (still 200) and unsubscribes
     * are reactivated. The unsubscribe_token is returned only the first
     * time an email is added so clients can include it in the footer
     * confirmation email.
     */
    public function subscribe(SubscribeRequest $request): JsonResponse
    {
        $subscriber = NewsletterSubscriber::query()
            ->firstOrNew(['email' => $request->validated('email')]);

        $subscriber->markSubscribed(
            locale: $request->validated('locale'),
            source: $request->validated('source'),
        );

        return $this->ok(message: __('messages.newsletter_subscribed'), data: [
            'email' => $subscriber->email,
            'status' => $subscriber->status?->value,
        ]);
    }

    /**
     * Unsubscribe an email using its unsubscribe_token (typically the
     * link included in the newsletter email). Returns 404 when the
     * email is not found and 403 when the token does not match, so
     * callers can't brute-force unsubscribe strangers.
     */
    public function unsubscribe(UnsubscribeRequest $request): JsonResponse
    {
        $subscriber = NewsletterSubscriber::query()
            ->where('email', $request->validated('email'))
            ->first();

        if ($subscriber === null) {
            return $this->notFound(__('messages.newsletter_subscriber_not_found'));
        }

        if (! hash_equals($subscriber->unsubscribe_token, (string) $request->validated('token'))) {
            return $this->forbidden(__('messages.newsletter_invalid_token'));
        }

        if ($subscriber->status !== NewsletterStatusEnum::Unsubscribed) {
            $subscriber->markUnsubscribed();
        }

        return $this->ok(message: __('messages.newsletter_unsubscribed'));
    }
}
