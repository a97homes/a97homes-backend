<?php

namespace App\Http\Requests\API\V1\EndUser\SellUnit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\Rules\Phone;

class StoreSellUnitRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'phone' => ['required', 'max:20', 'string', (new Phone)->international()],
            'sub_area_id' => ['required', Rule::exists('sub_areas', 'id')],
            'property_type_id' => ['required', Rule::exists('property_types', 'id')],
            'compound_id' => ['nullable', Rule::exists('compounds', 'id')],
            'notes' => ['nullable', 'string', 'max:2000'],
        ];
    }
}
