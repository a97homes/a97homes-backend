<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Newsletter;

use Illuminate\Foundation\Http\FormRequest;

class SendCampaignRequest extends FormRequest
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
            'subject' => ['required', 'string', 'max:255'],
            'body' => ['required', 'string', 'max:10000'],
            'cta_label' => ['nullable', 'string', 'max:100', 'required_with:cta_url'],
            'cta_url' => ['nullable', 'string', 'url', 'max:2048', 'required_with:cta_label'],
            'locale' => ['nullable', 'string', 'in:ar,en'],
        ];
    }
}
