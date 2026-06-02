<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Offer;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOfferRequest extends FormRequest
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
            'compound_id' => ['sometimes', Rule::exists('compounds', 'id')],
            'installment_years' => ['nullable', 'integer', 'min:1', 'max:50'],
            'down_payment_percentage' => ['nullable', 'numeric', 'min:0', 'max:100'],
            'monthly_payment' => ['nullable', 'integer', 'min:0'],
            'description' => ['nullable', 'array'],
            'description.ar' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'is_active' => ['sometimes', 'boolean'],
        ];
    }
}
