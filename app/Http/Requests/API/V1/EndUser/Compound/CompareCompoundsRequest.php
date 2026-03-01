<?php

namespace App\Http\Requests\API\V1\EndUser\Compound;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CompareCompoundsRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'compound_ids' => ['required', 'array', 'min:2', 'max:4'],
            'compound_ids.*' => ['required', 'integer', Rule::exists('compounds', 'id')],
        ];
    }
}
