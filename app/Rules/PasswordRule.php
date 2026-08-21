<?php

namespace App\Rules;

use App\Actions\Authentication\FindUserByCredentialsAction;
use Closure;
use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Support\Facades\Hash;

class PasswordRule implements ValidationRule
{
    public function __construct(
        public ?string $email = null,
        public ?string $phone = null,
        public ?string $countryCode = null,
    ) {}

    /**
     * Run the validation rule.
     *
     * @param  \Closure(string, ?string=): \Illuminate\Translation\PotentiallyTranslatedString  $fail
     */
    public function validate(string $attribute, mixed $value, Closure $fail): void
    {
        if (! $this->email && ! $this->phone) {
            return;
        }

        $user = app(FindUserByCredentialsAction::class)->execute($this->email, $this->phone, $this->countryCode);

        if (! $user || ! Hash::check($value, $user->password)) {
            $fail(__('auth.failed'));
        }
    }
}
