<?php

namespace App\Http\Requests\API\V1\Authentication;

use App\Actions\Authentication\FindUserByCredentialsAction;
use App\Models\User\User;
use App\Rules\PasswordRule;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;

abstract class LoginRequest extends FormRequest
{
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

    /**
     * @return array<int, callable>
     */
    public function after(): array
    {
        return [
            function (Validator $validator): void {
                if ($validator->errors()->isNotEmpty()) {
                    return;
                }

                $user = $this->resolveUser();

                if (! $user || $this->allowsPanelAccess($user)) {
                    return;
                }

                $validator->errors()->add($this->credentialAttribute(), __($this->accessDeniedMessageKey()));
            },
        ];
    }

    public function resolveUser(): ?User
    {
        return app(FindUserByCredentialsAction::class)->execute(
            $this->input('email'),
            $this->input('phone'),
            $this->input('country_code'),
        );
    }

    abstract protected function allowsPanelAccess(User $user): bool;

    abstract protected function accessDeniedMessageKey(): string;

    private function credentialAttribute(): string
    {
        return $this->filled('email') ? 'email' : 'phone';
    }
}
