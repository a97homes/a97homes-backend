<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Developer;

use Illuminate\Foundation\Http\FormRequest;

class BulkDeleteDeveloperRequest extends FormRequest
{
    private const MAX_IDS = 200;

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
            'ids' => ['required', 'array', 'min:1', 'max:'.self::MAX_IDS],
            'ids.*' => ['integer', 'distinct', 'exists:developers,id'],
        ];
    }

    /**
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'ids.required' => __('messages.developer_ids_required'),
            'ids.*.exists' => __('messages.developer_id_exists'),
        ];
    }

    /**
     * @return array<int, int>
     */
    public function developerIds(): array
    {
        /** @var array<int, int> $ids */
        $ids = array_map('intval', $this->validated('ids'));

        return $ids;
    }
}
