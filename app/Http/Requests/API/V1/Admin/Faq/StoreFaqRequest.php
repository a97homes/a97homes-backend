<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Faq;

use App\Models\City;
use App\Models\Compound;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFaqRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'faqable_type' => ['required', 'string', Rule::in(['city', 'compound'])],
            'faqable_id' => ['required', 'integer', $this->faqableExistsRule()],
            'question' => ['required', 'array'],
            'question.ar' => ['required', 'string'],
            'question.en' => ['required', 'string'],
            'answer' => ['required', 'array'],
            'answer.ar' => ['required', 'string'],
            'answer.en' => ['required', 'string'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }

    public function resolvedFaqableType(): string
    {
        return match ($this->input('faqable_type')) {
            'city' => City::class,
            'compound' => Compound::class,
            default => City::class,
        };
    }

    private function faqableExistsRule(): object
    {
        $type = $this->input('faqable_type');

        return $type === 'compound'
            ? Rule::exists('compounds', 'id')
            : Rule::exists('cities', 'id');
    }
}
