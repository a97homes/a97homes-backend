<?php

namespace App\Http\Requests\API\V1\Admin\City;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateCityRequest extends FormRequest
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
            'name.ar' => ['required', 'string', 'max:255', Rule::unique('countries', 'name->ar')->ignore($this->city->id)],
            'name.en' => ['required', 'string', 'max:255', Rule::unique('countries', 'name->en')->ignore($this->city->id)],
            'state_id' => ['required', Rule::exists('states', 'id')],
        ];
    }
}
