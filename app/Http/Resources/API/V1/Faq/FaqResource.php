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
     * location (sub area + area); a sub area exposes its area.
     *
     * @return array<string, mixed>
     */
    private function faqableContext(Model $faqable): array
    {
        $context = [];

        if ($faqable->relationLoaded('developer')) {
            $context['developer'] = $faqable->developer ? $this->idNamePayload($faqable->developer) : null;
        }

        if ($faqable->relationLoaded('subArea')) {
            $context['sub_area'] = $faqable->subArea ? $this->idNamePayload($faqable->subArea) : null;
            $area = $faqable->subArea?->relationLoaded('area') ? $faqable->subArea->area : null;
            $context['area'] = $area ? $this->idNamePayload($area) : null;
        } elseif ($faqable->relationLoaded('area')) {
            $context['area'] = $faqable->area ? $this->idNamePayload($faqable->area) : null;
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
