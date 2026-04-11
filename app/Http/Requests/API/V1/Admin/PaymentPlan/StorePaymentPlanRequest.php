<?php

namespace App\Http\Requests\API\V1\Admin\PaymentPlan;

use App\Enums\PaymentPlanTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePaymentPlanRequest extends FormRequest
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
            'compound_id' => ['required', Rule::exists('compounds', 'id')],
            'type' => ['required', 'string', Rule::in(array_column(PaymentPlanTypeEnum::cases(), 'value'))],
            'installment_years' => ['required', 'integer', 'min:1', 'max:30'],
            'down_payment' => ['required', 'integer', 'min:0'],
            'monthly_payment' => ['required', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ];
    }
}
