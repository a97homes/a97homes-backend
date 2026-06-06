<?php

namespace App\Http\Requests\API\V1\Admin\Country;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCountryRequest extends FormRequest
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
            'name.ar' => ['required', 'string', 'max:255', Rule::unique('countries', 'name->ar')],
            'name.en' => ['required', 'string', 'max:255', Rule::unique('countries', 'name->en')],
            'code' => ['required',  'string', 'max:2', Rule::unique('countries', 'code')],
            'flag' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.config('media-library.max_file_size')],
        ];
    }
}
