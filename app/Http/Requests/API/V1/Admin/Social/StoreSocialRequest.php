<?php

namespace App\Http\Requests\API\V1\Admin\Social;

use Illuminate\Foundation\Http\FormRequest;

class StoreSocialRequest extends FormRequest
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
            'type' => ['required', 'string', 'max:255'],
            'link' => ['required',
                'url',
                'max:255', ],
            'icon' => ['required', 'image',
                'mimes:png,jpg,svg',
                'max:'.config('media-library.max_file_size')],
        ];
    }
}
