<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Faq;

use Illuminate\Foundation\Http\FormRequest;

class UpdateFaqRequest extends FormRequest
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
            'question' => ['sometimes', 'array'],
            'question.ar' => ['required_with:question', 'string'],
            'question.en' => ['required_with:question', 'string'],
            'answer' => ['sometimes', 'array'],
            'answer.ar' => ['required_with:answer', 'string'],
            'answer.en' => ['required_with:answer', 'string'],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
