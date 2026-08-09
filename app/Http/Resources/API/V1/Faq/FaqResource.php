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

        return array_merge(
            $this->idNamePayload($faqable, ['type' => $faq->faqable_type]),
            $this->faqableContext($faqable),
        );
    }

    /**
     * Parent breadcrumb for the owner: a compound exposes its developer and
     * area (city + state); a city (area) exposes its state.
     *
     * @return array<string, mixed>
     */
    private function faqableContext(Model $faqable): array
    {
        $context = [];

        if ($faqable->relationLoaded('developer')) {
            $context['developer'] = $faqable->developer ? $this->idNamePayload($faqable->developer) : null;
        }

        if ($faqable->relationLoaded('city')) {
            $context['area'] = $faqable->city ? $this->idNamePayload($faqable->city) : null;
            $state = $faqable->city?->relationLoaded('state') ? $faqable->city->state : null;
            $context['state'] = $state ? $this->idNamePayload($state) : null;
        } elseif ($faqable->relationLoaded('state')) {
            $context['state'] = $faqable->state ? $this->idNamePayload($faqable->state) : null;
        }

        return $context;
    }

    /**
     * @param  array<string, mixed>  $extra
     * @return array<string, mixed>
     */
    private function idNamePayload(Model $model, array $extra = []): array
    {
        return array_merge($extra, [
            'id' => $model->getKey(),
            'name' => $this->resolveName($model),
        ]);
    }

    /**
     * @return mixed
     */
    private function resolveName(Model $model)
    {
        $translatable = property_exists($model, 'translatable') ? $model->translatable : [];

        if (in_array('name', $translatable, true)) {
            return $this->getTranslatableField($model, 'name');
        }

        return $model->name;
    }
}
