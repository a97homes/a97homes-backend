<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Phase;

use App\Enums\CompletionStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StorePhaseRequest extends FormRequest
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
            'compound_id' => ['required', Rule::exists('compounds', 'id')],
            'name' => ['required', 'array'],
            'name.ar' => ['required', 'string', 'max:255'],
            'name.en' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'array'],
            'description.ar' => ['nullable', 'string'],
            'description.en' => ['nullable', 'string'],
            'delivery_date' => ['nullable', 'date'],
            'completion_status' => ['nullable', new Enum(CompletionStatusEnum::class)],
            'sort_order' => ['nullable', 'integer', 'min:0'],
        ];
    }
}
