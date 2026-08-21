<?php

declare(strict_types=1);

namespace App\Models;

use App\Enums\NewsletterStatusEnum;
use App\Filters\CreatedAtFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class NewsletterSubscriber extends Model
{
    use CreatedAtFilter;

    /** @use HasFactory<\Database\Factories\NewsletterSubscriberFactory> */
    use HasFactory;

    protected $fillable = [
        'email',
        'locale',
        'status',
        'subscribed_at',
        'unsubscribed_at',
        'unsubscribe_token',
        'source',
    ];

    protected function casts(): array
    {
        return [
            'status' => NewsletterStatusEnum::class,
            'subscribed_at' => 'datetime',
            'unsubscribed_at' => 'datetime',
        ];
    }

    public static function freshToken(): string
    {
        return Str::random(48);
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', NewsletterStatusEnum::Active);
    }

    public function markSubscribed(?string $locale = null, ?string $source = null): self
    {
        $this->fill([
            'status' => NewsletterStatusEnum::Active,
            'subscribed_at' => now(),
            'unsubscribed_at' => null,
            'locale' => $locale ?: $this->locale ?: app()->getLocale(),
            'source' => $source ?: $this->source,
        ]);

        if (empty($this->unsubscribe_token)) {
            $this->unsubscribe_token = self::freshToken();
        }

        $this->save();

        return $this;
    }

    public function markUnsubscribed(): self
    {
        $this->fill([
            'status' => NewsletterStatusEnum::Unsubscribed,
            'unsubscribed_at' => now(),
        ])->save();

        return $this;
    }
}
