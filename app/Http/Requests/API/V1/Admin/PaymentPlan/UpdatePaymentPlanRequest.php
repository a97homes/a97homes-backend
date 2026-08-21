<?php

namespace App\Http\Requests\API\V1\Admin\PaymentPlan;

use App\Enums\PaymentPlanTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePaymentPlanRequest extends FormRequest
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
            'type' => ['sometimes', 'string', Rule::in(array_column(PaymentPlanTypeEnum::cases(), 'value'))],
            'installment_years' => ['sometimes', 'integer', 'min:1', 'max:30'],
            'down_payment' => ['sometimes', 'integer', 'min:0'],
            'monthly_payment' => ['sometimes', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
