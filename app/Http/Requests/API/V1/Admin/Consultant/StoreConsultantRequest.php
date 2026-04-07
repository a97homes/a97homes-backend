<?php

namespace App\Http\Requests\API\V1\Admin\Consultant;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultantRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:255'],
            'job_title' => ['required', 'string', 'max:255'],
            'sales_percentage' => ['required', 'numeric', 'min:0', 'max:100'],
            'image' => ['nullable', 'string', 'max:2048'],
            'is_featured' => ['sometimes', 'boolean'],
            'phones' => ['required', 'array', 'min:1'],
            'phones.*' => ['required', 'string', 'max:20'],
        ];
    }
}
