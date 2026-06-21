<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Faq;

use App\Models\Faq;
use App\Traits\HasTranslatableFields;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class FaqResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Faq $faq */
        $faq = $this->resource;

        return [
            'id' => $faq->id,
            'question' => $this->getTranslatableField($faq, 'question'),
            'answer' => $this->getTranslatableField($faq, 'answer'),
            'sort_order' => $faq->sort_order,
            'is_active' => $faq->is_active,
            'faqable_type' => $faq->faqable_type,
            'faqable_id' => $faq->faqable_id,
            'faqable' => $this->faqablePayload($faq),
        ];
    }

    /**
     * @return array<string, mixed>|null
     */
    private function faqablePayload(Faq $faq): ?array
    {
        $faqable = $faq->faqable;

        if (! $faqable instanceof Model) {
            return null;
        }

        return [
            'type' => $faq->faqable_type,
            'id' => $faqable->getKey(),
            'name' => $this->resolveFaqableName($faqable),
        ];
    }

    /**
     * @return mixed
     */
    private function resolveFaqableName(Model $faqable)
    {
        $translatable = property_exists($faqable, 'translatable') ? $faqable->translatable : [];

        if (in_array('name', $translatable, true)) {
            return $this->getTranslatableField($faqable, 'name');
        }

        return $faqable->name;
    }
}
