<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Faq;

use App\Models\Faq;
use App\Traits\HasTranslatableFields;
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
        ];
    }
}
