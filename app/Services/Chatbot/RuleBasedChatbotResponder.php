<?php

declare(strict_types=1);

namespace App\Services\Chatbot;

use App\Models\Faq;
use App\Traits\HasArabicSearch;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

/**
 * Deterministic chatbot — no external service required. Matches a
 * handful of canonical intents in Arabic + English, then falls back
 * to an FAQ lookup before returning a generic help message. The return
 * shape is the same one an LLM adapter would produce so swapping in
 * a real model is a one-line container binding.
 */
class RuleBasedChatbotResponder implements ChatbotResponder
{
    use HasArabicSearch;

    private const INTENTS = [
        'greeting' => [
            'keywords' => ['hi', 'hello', 'hey', 'مرحبا', 'اهلا', 'السلام'],
        ],
        'help' => [
            'keywords' => ['help', 'support', 'assist', 'مساعده', 'مساعدة', 'ساعدني'],
        ],
        'search' => [
            'keywords' => ['search', 'find', 'looking', 'ابحث', 'بحث', 'عايز', 'ادور'],
        ],
        'pricing' => [
            'keywords' => ['price', 'cost', 'cheap', 'expensive', 'سعر', 'اسعار', 'تكلفه'],
        ],
        'location' => [
            'keywords' => ['where', 'location', 'area', 'فين', 'اين', 'منطقه', 'موقع'],
        ],
        'payment' => [
            'keywords' => ['payment', 'installment', 'mortgage', 'تقسيط', 'قسط', 'مقدم', 'دفعه'],
        ],
        'contact' => [
            'keywords' => ['contact', 'call', 'reach', 'تواصل', 'اتصل', 'رقم'],
        ],
    ];

    /**
     * {@inheritdoc}
     */
    public function reply(string $userMessage, string $locale, array $history = []): array
    {
        $normalized = $this->normalize($userMessage);
        $intent = $this->matchIntent($normalized);

        if ($intent !== null) {
            return $this->cannedResponse($intent, $locale);
        }

        $faqMatch = $this->findFaqMatch($userMessage);
        if ($faqMatch !== null) {
            return [
                'intent' => 'faq',
                'reply' => $faqMatch->getTranslation('answer', $locale),
                'suggestions' => $this->defaultSuggestions($locale),
                'metadata' => ['faq_id' => $faqMatch->id],
            ];
        }

        return [
            'intent' => 'fallback',
            'reply' => $locale === 'ar'
                ? 'أنا آسف، لم أفهم سؤالك تمامًا. يمكنني مساعدتك في البحث عن كمبوندات، الأسعار، خطط السداد، والتواصل مع مستشار.'
                : "I didn't quite catch that. I can help you search for compounds, pricing, payment plans, and connecting with a consultant.",
            'suggestions' => $this->defaultSuggestions($locale),
            'metadata' => [],
        ];
    }

    private function matchIntent(string $normalized): ?string
    {
        foreach (self::INTENTS as $intent => $config) {
            foreach ($config['keywords'] as $keyword) {
                if (Str::contains($normalized, $this->normalize($keyword))) {
                    return $intent;
                }
            }
        }

        return null;
    }

    /**
     * @return array{intent: string, reply: string, suggestions: array<int, string>, metadata: array<string, mixed>}
     */
    private function cannedResponse(string $intent, string $locale): array
    {
        $ar = [
            'greeting' => 'أهلًا بك في A97! كيف يمكنني مساعدتك اليوم؟ ابحث عن كمبوند، احسب قسطك الشهري، أو اختر مستشارك.',
            'help' => 'أكيد! يمكنني مساعدتك في: البحث عن كمبوندات، مقارنة العقارات، حساب القسط، أو إيجاد مستشار موثوق.',
            'search' => 'رائع! جرّب بحثنا السريع عبر /api/V1/search أو اختر منطقة من صفحة المناطق. أيّ مدينة تفكر فيها؟',
            'pricing' => 'الأسعار تختلف حسب المنطقة والمطور. استخدم فلاتر السعر في قائمة الكمبوندات أو احسب القسط عبر المحاسبة العقارية.',
            'location' => 'تصفّح المناطق الشعبية من /api/V1/popular-areas، أو افتح صفحة المنطقة للاطلاع على الكمبوندات والعروض.',
            'payment' => 'يمكنك حساب قسطك الشهري بإرسال السعر، نسبة المقدم، وعدد السنوات إلى /api/V1/mortgage/calculate.',
            'contact' => 'نرحّب بتواصلك! استخدم نموذج التواصل عبر /api/V1/contact أو زر معلومات الشركة لأرقامنا.',
        ];

        $en = [
            'greeting' => 'Welcome to A97! How can I help you today? Search for a compound, calculate a monthly instalment, or pick a consultant.',
            'help' => 'Of course! I can help you search compounds, compare properties, estimate monthly payments, or find a trusted consultant.',
            'search' => 'Great! Try our quick search via /api/V1/search, or pick an area from the popular areas list. Which city are you considering?',
            'pricing' => 'Pricing varies by area and developer. Use the price filter on the compounds listing, or estimate a mortgage via /api/V1/mortgage/calculate.',
            'location' => 'Browse popular areas at /api/V1/popular-areas, or open an area detail page for its compounds and offers.',
            'payment' => 'Calculate your monthly instalment by sending price, down-payment percentage, and years to /api/V1/mortgage/calculate.',
            'contact' => 'We would love to hear from you! Use the contact form at /api/V1/contact or check /api/V1/company-info for direct numbers.',
        ];

        $table = $locale === 'ar' ? $ar : $en;

        return [
            'intent' => $intent,
            'reply' => $table[$intent] ?? ($table['help'] ?? ''),
            'suggestions' => $this->defaultSuggestions($locale),
            'metadata' => [],
        ];
    }

    private function findFaqMatch(string $value): ?Faq
    {
        $normalized = $this->normalizeArabicText($value);
        $driver = DB::connection()->getDriverName();

        $query = Faq::query()->where('is_active', true);

        if ($driver === 'sqlite') {
            $query->where(function ($q) use ($normalized, $value): void {
                $q->whereRaw("json_extract(question, '$.ar') LIKE ?", ["%{$value}%"])
                    ->orWhereRaw("json_extract(question, '$.ar') LIKE ?", ["%{$normalized}%"])
                    ->orWhereRaw("json_extract(question, '$.en') LIKE ?", ["%{$value}%"]);
            });
        } else {
            $query->where(function ($q) use ($value): void {
                $q->whereRaw("question->>'ar' LIKE ?", ["%{$value}%"])
                    ->orWhereRaw("question->>'en' LIKE ?", ["%{$value}%"]);
            });
        }

        return $query->first();
    }

    /**
     * @return array<int, string>
     */
    private function defaultSuggestions(string $locale): array
    {
        return $locale === 'ar'
            ? ['ابحث عن كمبوند', 'احسب قسطي الشهري', 'اختر مستشاري', 'تواصل معنا']
            : ['Search compounds', 'Calculate monthly payment', 'Pick a consultant', 'Contact us'];
    }

    private function normalize(string $value): string
    {
        return mb_strtolower($this->normalizeArabicText($value));
    }
}
