<?php

declare(strict_types=1);

namespace App\Models;

use App\Models\User\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class ChatbotConversation extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'locale',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function messages(): HasMany
    {
        return $this->hasMany(ChatbotMessage::class, 'conversation_id');
    }

    public static function findOrCreateForRequest(?int $userId, ?string $sessionId, string $locale): self
    {
        $sessionId = $sessionId ?: (string) Str::uuid();

        // Authenticated users keep a single conversation per session_id;
        // guests are tracked purely by session_id.
        $query = static::query()->where('session_id', $sessionId);

        if ($userId !== null) {
            $query->where('user_id', $userId);
        } else {
            $query->whereNull('user_id');
        }

        $conversation = $query->first();

        if ($conversation === null) {
            $conversation = static::create([
                'user_id' => $userId,
                'session_id' => $sessionId,
                'locale' => $locale,
            ]);
        }

        return $conversation;
    }
}
