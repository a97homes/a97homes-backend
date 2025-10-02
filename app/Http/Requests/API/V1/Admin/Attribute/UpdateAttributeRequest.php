<?php

namespace App\Http\Requests\API\V1\Admin\Attribute;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAttributeRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'array'],
            'name.ar' => ['required', 'string', 'max:255', Rule::unique('attributes', 'name->ar')->ignore($this->attribute->id)],
            'name.en' => ['required', 'string', 'max:255', Rule::unique('attributes', 'name->en')->ignore($this->attribute->id)],
            'type' => ['required', 'string', 'max:255'],
            'unit_id' => ['required', Rule::exists('units', 'id')],

        ];
    }
}
