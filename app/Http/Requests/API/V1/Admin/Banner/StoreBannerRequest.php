<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Banner;

use Illuminate\Foundation\Http\FormRequest;

class StoreBannerRequest extends FormRequest
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
            'title' => ['required', 'array'],
            'title.ar' => ['required', 'string', 'max:255'],
            'title.en' => ['required', 'string', 'max:255'],
            'subtitle' => ['nullable', 'array'],
            'subtitle.ar' => ['nullable', 'string', 'max:255'],
            'subtitle.en' => ['nullable', 'string', 'max:255'],
            'link' => ['nullable', 'string', 'max:2048', 'url'],
            'is_active' => ['nullable', 'boolean'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'image' => ['required', 'file', 'mimes:jpg,jpeg,png,webp', 'max:'.config('media-library.max_file_size')],
        ];
    }
}
