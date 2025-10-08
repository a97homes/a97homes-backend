<?php

namespace App\Http\Requests\API\V1\EndUser\Contact;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Propaganistas\LaravelPhone\Rules\Phone;

class ContactRequest extends FormRequest
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
            'name' => ['required', 'string', 'max:100'],
            'email' => ['required', 'email', 'max:150'], // TODO: https://laravel.com/docs/12.x/validation
            'phone' => ['nullable', 'max:20', 'string',
                (new Phone)->international(), Rule::unique('contacts', 'phone')],
            'message' => ['required', 'string', 'max:1000'],
        ];
    }
}
