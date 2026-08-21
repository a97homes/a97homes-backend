<?php

namespace App\Http\Requests\Concerns;

use App\Support\ContactMethodNormalizer;
use Illuminate\Validation\Validator;

trait ValidatesContactMethods
{
    protected function prepareContactMethodsForValidation(): void
    {
        $updates = [];

        foreach (['phones', 'whatsapp_numbers'] as $field) {
            if ($this->has($field) && is_array($this->input($field))) {
                $updates[$field] = ContactMethodNormalizer::normalizeMany($this->input($field));
            }
        }

        if ($updates !== []) {
            $this->merge($updates);
        }
    }

    /**
     * @return array<string, array<int, mixed>>
     */
    protected function contactMethodRules(bool $required = false): array
    {
        $rootRule = $required ? 'required' : 'sometimes';

        return [
            'phones' => [$rootRule, 'array'],
            'phones.*.country_code' => ['required', 'string', 'regex:/^\+[1-9]\d{0,3}$/'],
            'phones.*.number' => ['required', 'string', 'regex:/^[0-9]{4,20}$/'],
            'phones.*.is_primary' => ['sometimes', 'boolean'],
            'phones.*.sort_order' => ['sometimes', 'integer', 'min:0'],
            'whatsapp_numbers' => [$rootRule, 'array'],
            'whatsapp_numbers.*.country_code' => ['required', 'string', 'regex:/^\+[1-9]\d{0,3}$/'],
            'whatsapp_numbers.*.number' => ['required', 'string', 'regex:/^[0-9]{4,20}$/'],
            'whatsapp_numbers.*.is_primary' => ['sometimes', 'boolean'],
            'whatsapp_numbers.*.sort_order' => ['sometimes', 'integer', 'min:0'],
        ];
    }

    public function withValidator(Validator $validator): void
    {
        $validator->after(function (Validator $validator): void {
            foreach (['phones', 'whatsapp_numbers'] as $field) {
                $contacts = $this->input($field);

                if (! is_array($contacts)) {
                    continue;
                }

                $seen = [];
                $primaryCount = 0;

                foreach ($contacts as $index => $contact) {
                    if (! is_array($contact)) {
                        continue;
                    }

                    $key = ($contact['country_code'] ?? '').'|'.($contact['number'] ?? '');

                    if (isset($seen[$key])) {
                        $validator->errors()->add("{$field}.{$index}.number", 'The contact number is duplicated in this request.');
                    }

                    $seen[$key] = true;

                    if ((bool) ($contact['is_primary'] ?? false)) {
                        $primaryCount++;
                    }
                }

                if ($primaryCount > 1) {
                    $validator->errors()->add($field, 'Only one contact number may be primary.');
                }
            }
        });
    }
}
