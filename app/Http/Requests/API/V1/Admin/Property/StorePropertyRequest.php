<?php

namespace App\Http\Requests\API\V1\Admin\Property;

use App\Enums\PropertyStatusEnum;
use App\Enums\SaleTypeEnum;
use App\Http\Requests\Concerns\ValidatesContactMethods;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePropertyRequest extends FormRequest
{
    use ValidatesContactMethods;

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
            'name' => ['array', 'required'],
            'name.ar' => ['required', 'string', 'max:255'],
            'name.en' => ['required', 'string', 'max:255'],
            'attributes_ids' => ['required', 'array'],
            'attributes_ids.*' => ['required', Rule::exists('attributes', 'id')],
            'attribute_values' => ['nullable', 'array'],
            'attribute_values.*' => ['nullable', 'string', 'max:255'],
            'option_ids' => ['nullable', 'array'],
            'option_ids.*' => ['required', Rule::exists('attribute_options', 'id')],
            'sub_area_id' => ['required', Rule::exists('sub_areas', 'id')],
            'order_id' => ['nullable', Rule::exists('orders', 'id')],
            'property_type_id' => ['required', Rule::exists('property_types', 'id')],
            'compound_id' => ['required', 'integer', Rule::exists('compounds', 'id')],
            'consultant_id' => ['nullable', 'integer', Rule::exists('consultants', 'id')],
            'address' => ['required', 'string', 'max:255'],
            'price' => ['nullable', 'integer', 'min:0'],
            'resale_price' => ['nullable', 'integer', 'min:0'],
            'sale_type' => ['nullable', Rule::enum(SaleTypeEnum::class)],
            'status' => ['nullable', Rule::enum(PropertyStatusEnum::class)],
            'latitude' => ['required', 'numeric', 'between:-90,90'],
            'longitude' => ['required', 'numeric', 'between:-180,180'],
            'is_featured' => ['nullable', 'boolean'],
            ...$this->contactMethodRules(),
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->prepareContactMethodsForValidation();
    }
}
