<?php

namespace App\Http\Requests\API\V1\Admin\Compound;

use Illuminate\Foundation\Http\FormRequest;

class AddCompoundMediaRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
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
