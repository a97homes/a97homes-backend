<?php

namespace App\Http\Requests\API\V1\Admin\Consultant;

use Illuminate\Foundation\Http\FormRequest;

class UpdateConsultantRequest extends FormRequest
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
            'name' => ['sometimes', 'string', 'max:255'],
            'job_title' => ['sometimes', 'string', 'max:255'],
            'sales_percentage' => ['sometimes', 'numeric', 'min:0', 'max:100'],
            'image' => ['nullable', 'image', 'max:5120'],
            'cover_image' => ['nullable', 'image', 'max:5120'],
            'is_featured' => ['sometimes', 'boolean'],
            'phones' => ['sometimes', 'array', 'min:1'],
            'phones.*' => ['required', 'string', 'max:20'],
        ];
    }
}
