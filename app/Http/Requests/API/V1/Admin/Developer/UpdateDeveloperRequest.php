<?php

namespace App\Http\Requests\API\V1\Admin\Developer;

use App\Http\Requests\Concerns\ValidatesContactMethods;
use App\Support\ContactMethodNormalizer;
use Illuminate\Foundation\Http\FormRequest;

class UpdateDeveloperRequest extends FormRequest
{
    use ValidatesContactMethods;

    private const ABOUT_MAX_LENGTH = 50000;

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
            'name' => ['sometimes', 'array'],
            'name.ar' => ['required_with:name', 'string', 'max:255'],
            'name.en' => ['nullable', 'string', 'max:255'],
            'about' => ['sometimes', 'array'],
            'about.ar' => ['required_with:about', 'string', 'max:'.self::ABOUT_MAX_LENGTH],
            'about.en' => ['nullable', 'string', 'max:'.self::ABOUT_MAX_LENGTH],
            'whatsapp' => ['nullable', 'string', 'max:30'],
            'phone' => ['nullable', 'string', 'max:30'],
            ...$this->contactMethodRules(),
            'is_active' => ['sometimes', 'boolean'],
            'logo' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:'.config('media-library.max_file_size')],
            'banner' => ['nullable', 'file', 'mimes:jpg,jpeg,png', 'max:'.config('media-library.max_file_size')],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->prepareLegacyContactFields();
        $this->prepareContactMethodsForValidation();
    }

    private function prepareLegacyContactFields(): void
    {
        $updates = [];

        if (! $this->has('phones') && $this->filled('phone')) {
            $contact = ContactMethodNormalizer::splitLegacy($this->input('phone'));

            if ($contact !== null) {
                $updates['phones'] = [$contact + ['is_primary' => true]];
            }
        }

        if (! $this->has('whatsapp_numbers') && $this->filled('whatsapp')) {
            $contact = ContactMethodNormalizer::splitLegacy($this->input('whatsapp'));

            if ($contact !== null) {
                $updates['whatsapp_numbers'] = [$contact + ['is_primary' => true]];
            }
        }

        if ($updates !== []) {
            $this->merge($updates);
        }
    }
}
