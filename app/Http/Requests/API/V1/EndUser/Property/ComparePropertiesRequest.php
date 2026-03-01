<?php

namespace App\Http\Requests\API\V1\EndUser\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ComparePropertiesRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'property_ids' => ['required', 'array', 'min:2', 'max:4'],
            'property_ids.*' => ['required', 'integer', Rule::exists('properties', 'id')],
        ];
    }
}
