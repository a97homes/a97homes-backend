<?php

namespace App\Http\Requests\API\V1\Admin\Property;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePropertyRequest extends FormRequest
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
            'name' => ['array', 'required'],
            'name.ar' => ['required', 'string', 'max:255'],
            'name.en' => ['required', 'string', 'max:255'],
            'attributes_ids' => ['required', 'array'],
            'attributes_ids.*' => ['required', Rule::exists('attributes', 'id')],
            'city_id' => ['required', Rule::exists('cities', 'id')],
            'order_id' => ['required', Rule::exists('orders', 'id')],
            'property_type_id' => ['required', Rule::exists('property_types', 'id')],
            'project_id' => ['required', 'integer', Rule::exists('projects', 'id')],
            'phase_id' => ['nullable', 'integer', Rule::exists('phases', 'id')],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],

        ];
    }
}
