<?php

namespace App\Http\Requests\API\V1\Admin\Developer;

use Illuminate\Foundation\Http\FormRequest;

class UpdateDeveloperRequest extends FormRequest
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
            'name' => ['sometimes', 'array'],
            'name.ar' => ['required_with:name', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'about' => ['sometimes', 'array'],
            'about.ar' => ['required_with:about', 'string', 'max:10000'],
            'about.en' => ['nullable', 'string', 'max:10000'],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['sometimes', 'boolean'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:'.config('media-library.max_file_size')],
            'banner' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:'.config('media-library.max_file_size')],
        ];
    }
}
