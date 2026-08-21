<?php

namespace App\Http\Requests\API\V1\Admin\Area;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreAreaRequest extends FormRequest
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
            'country_id' => ['required', Rule::exists('countries', 'id')],
            'name.ar' => ['required', 'string', 'max:255', Rule::unique('areas', 'name->ar')],
            'name.en' => ['required', 'string', 'max:255', Rule::unique('areas', 'name->en')],
            'about' => ['nullable', 'array'],
            'about.ar' => ['nullable', 'string'],
            'about.en' => ['nullable', 'string'],
        ];
    }
}
