<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\EndUser\Newsletter;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class SubscribeRequest extends FormRequest
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
            'email' => ['required', 'email', 'max:255'],
            'locale' => ['nullable', Rule::in(['ar', 'en'])],
            'source' => ['nullable', 'string', 'max:40'],
        ];
    }
}
