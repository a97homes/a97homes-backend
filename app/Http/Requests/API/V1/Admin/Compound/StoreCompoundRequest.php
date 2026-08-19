<?php

namespace App\Http\Requests\API\V1\Admin\Compound;

use App\Enums\CompletionStatusEnum;
use App\Http\Requests\Concerns\ValidatesContactMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreCompoundRequest extends FormRequest
{
    use ValidatesContactMethods;

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
            'developer_id' => ['required', Rule::exists('developers', 'id')],
            'city_id' => ['nullable', 'integer', Rule::exists('cities', 'id')],
            'completion_status' => ['nullable', 'string', Rule::in(array_column(CompletionStatusEnum::cases(), 'value'))],
            'description' => ['nullable', 'array'],
            'description.en' => ['nullable', 'string'],
            'description.ar' => ['nullable', 'string'],
            'delivery_date' => ['nullable', 'date'],
            'is_featured' => ['nullable', 'boolean'],
            ...$this->contactMethodRules(),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->prepareContactMethodsForValidation();
    }
}
