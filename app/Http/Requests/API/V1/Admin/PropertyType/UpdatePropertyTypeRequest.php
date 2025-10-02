<?php

namespace App\Http\Requests\API\V1\Admin\PropertyType;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePropertyTypeRequest extends FormRequest
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
            'name.ar' => ['required', 'string', 'max:255', Rule::unique('property_types', 'name->ar')->ignore($this->route('property_type'))],
            'name.en' => ['required', 'string', 'max:255', Rule::unique('property_types', 'name->en')->ignore($this->route('property_type'))],
        ];
    }
}
