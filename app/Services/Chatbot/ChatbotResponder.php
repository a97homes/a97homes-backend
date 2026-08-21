<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

/**
 * Contract for producing a chatbot reply. The default implementation
 * is rule-based (RuleBasedChatbotResponder). An LLM-backed adapter
 * can be bound in the service container later without changing any
 * callers.
 */
interface ChatbotResponder
{
    /**
     * @param  array<int, array{role: string, content: string}>  $history
     * @return array{intent: string, reply: string, suggestions: array<int, string>, metadata: array<string, mixed>}
     */
    public function reply(string $userMessage, string $locale, array $history = []): array;
}
