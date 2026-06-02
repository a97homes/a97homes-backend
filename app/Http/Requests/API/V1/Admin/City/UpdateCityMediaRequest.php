<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\City;

use Illuminate\Foundation\Http\FormRequest;

class UpdateCityMediaRequest extends FormRequest
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
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:'.config('media-library.max_file_size')],
        ];
    }
}
