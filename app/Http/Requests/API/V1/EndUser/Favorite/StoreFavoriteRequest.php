<?php

namespace App\Http\Requests\API\V1\EndUser\Favorite;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreFavoriteRequest extends FormRequest
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
            'compound_id' => ['required', 'integer', Rule::exists('compounds', 'id')],
        ];
    }
}
