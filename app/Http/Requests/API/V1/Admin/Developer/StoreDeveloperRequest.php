<?php

namespace App\Http\Requests\API\V1\Admin\Developer;

use Illuminate\Foundation\Http\FormRequest;

class StoreDeveloperRequest extends FormRequest
{
    private const ABOUT_MAX_LENGTH = 50000;

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
            'name' => ['required', 'array'],
            'name.ar' => ['required', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'about' => ['required', 'array'],
            'about.ar' => ['required', 'string', 'max:'.self::ABOUT_MAX_LENGTH],
            'about.en' => ['nullable', 'string', 'max:'.self::ABOUT_MAX_LENGTH],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'phone' => ['nullable', 'string', 'max:30'],
            'is_active' => ['sometimes', 'boolean'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:'.config('media-library.max_file_size')],
            'banner' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:'.config('media-library.max_file_size')],
        ];
    }
}
