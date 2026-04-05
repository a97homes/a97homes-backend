<?php

namespace App\Http\Requests\API\V1\Admin\Property;

use App\Enums\PropertyStatusEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdatePropertyStatusRequest extends FormRequest
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
            'status' => [
                'required',
                Rule::enum(PropertyStatusEnum::class)->only([PropertyStatusEnum::ACTIVE, PropertyStatusEnum::BLOCKED]),
            ],
        ];
    }
}
