<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\EndUser\SavedSearch;

use App\Enums\SavedSearchTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rules\Enum;

class UpdateSavedSearchRequest extends FormRequest
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
            'name' => ['sometimes', 'nullable', 'string', 'max:120'],
            'type' => ['sometimes', new Enum(SavedSearchTypeEnum::class)],
            'criteria' => ['sometimes', 'array'],
            'notify_by_email' => ['sometimes', 'boolean'],
        ];
    }
}
