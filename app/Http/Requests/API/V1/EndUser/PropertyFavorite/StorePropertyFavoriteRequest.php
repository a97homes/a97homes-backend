<?php

namespace App\Http\Requests\API\V1\EndUser\PropertyFavorite;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePropertyFavoriteRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    /**
     * @return array<string, mixed>
     */
    public function rules(): array
    {
        return [
            'property_id' => ['required', 'integer', Rule::exists('properties', 'id')],
        ];
    }
}
