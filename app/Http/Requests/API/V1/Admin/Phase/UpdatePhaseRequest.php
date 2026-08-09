<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Phase;

use App\Enums\CompletionStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdatePhaseRequest extends FormRequest
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
            'compound_id' => ['sometimes', Rule::exists('compounds', 'id')],
            'name' => ['sometimes', 'array'],
            'name.ar' => ['required_with:name', 'string', 'max:255'],
            'name.en' => ['required_with:name', 'string', 'max:255'],
            'description' => ['sometimes', 'array'],
            'description.ar' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'delivery_date' => ['sometimes', 'nullable', 'date'],
            'completion_status' => ['sometimes', new Enum(CompletionStatusEnum::class)],
            'sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }
}
