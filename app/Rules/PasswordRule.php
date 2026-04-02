<?php

namespace App\Rules;

use App\Models\User\User;
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

        $user = User::query()
            ->when($this->email, fn ($query) => $query->where('email', $this->email))
            ->when(! $this->email && $this->phone, fn ($query) => $query->where('phone', $this->phone)->where('country_code', $this->countryCode))
            ->first();

        if (! $user || ! Hash::check($value, $user->password)) {
            $fail(__('auth.failed'));
        }
    }
}
