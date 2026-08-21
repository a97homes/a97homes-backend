<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\EndUser\Mortgage;

use Illuminate\Foundation\Http\FormRequest;

class CalculateMortgageRequest extends FormRequest
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
            'price' => ['required', 'numeric', 'min:1'],
            'down_payment_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'years' => ['required', 'integer', 'min:1', 'max:40'],
            'annual_interest_rate' => ['nullable', 'numeric', 'min:0', 'max:100'],
        ];
    }
}
