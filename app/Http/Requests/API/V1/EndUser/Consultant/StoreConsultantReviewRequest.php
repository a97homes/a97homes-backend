<?php

namespace App\Http\Requests\API\V1\EndUser\Consultant;

use Illuminate\Foundation\Http\FormRequest;

class StoreConsultantReviewRequest extends FormRequest
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
            'title' => ['required', 'string', 'max:255'],
            'comment' => ['required', 'string', 'max:5000'],
            'overall_rating' => ['required', 'integer', 'min:1', 'max:5'],
            'local_knowledge_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'process_expertise_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'response_speed_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
            'negotiation_skills_rating' => ['nullable', 'integer', 'min:1', 'max:5'],
        ];
    }
}
