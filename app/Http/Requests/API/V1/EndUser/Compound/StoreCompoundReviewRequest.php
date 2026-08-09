<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\EndUser\Compound;

use Illuminate\Foundation\Http\FormRequest;

class StoreCompoundReviewRequest extends FormRequest
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
            'title' => ['nullable', 'string', 'max:255'],
            'comment' => ['nullable', 'string', 'max:5000'],
            'overall_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'location_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'amenities_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'value_for_money_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'developer_reputation_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        ];
    }
}
