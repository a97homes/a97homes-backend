<?php

namespace App\Http\Requests\API\V1\Admin\Property;

use Illuminate\Foundation\Http\FormRequest;

class AddPropertyMediaRequest extends FormRequest
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
            'file' => [
                'required',
                'file',
                'mimes:jpg,jpeg,png,pdf',
                'max:'.config('media-library.max_file_size'),

            ],

        ];
    }
}
