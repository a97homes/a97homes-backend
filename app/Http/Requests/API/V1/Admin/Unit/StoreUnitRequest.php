<?php

namespace App\Http\Requests\API\V1\Admin\Unit;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StoreUnitRequest extends FormRequest
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
            'name' => ['required', 'array'],
            'name.ar' => ['required', 'string', 'max:255', Rule::unique('units', 'name->ar')],
            'name.en' => ['required', 'string', 'max:255', Rule::unique('units', 'name->en')],
            'symbol' => ['required', 'array'],
            'symbol.ar' => ['required', 'string', 'max:50', Rule::unique('units', 'symbol->ar')],
            'symbol.en' => ['required', 'string', 'max:50', Rule::unique('units', 'symbol->en')],
            'type' => ['string', 'required', 'max:255'],

        ];
    }
}
