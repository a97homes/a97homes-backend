<?php

namespace App\Http\Requests;

use App\Rules\PasswordRule;
use Illuminate\Foundation\Http\FormRequest;

class LoginRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    protected function prepareForValidation(): void
    {
        if ($this->phone) {
            $this->merge([
                'phone' => ltrim($this->phone, '0'),
            ]);
        }
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'email' => ['required_without:phone', 'nullable', 'email'],
            'country_code' => ['required_with:phone', 'nullable', 'string', 'max:10'],
            'phone' => ['required_without:email', 'nullable', 'string'],
            'password' => ['required', 'string', new PasswordRule($this->email, $this->phone, $this->country_code)],
        ];
    }
}
