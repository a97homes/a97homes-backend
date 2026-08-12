<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Developer;

class BulkUpdateDeveloperStatusRequest extends BulkDeleteDeveloperRequest
{
    /**
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return array_merge(parent::rules(), [
            'is_active' => ['required', 'boolean'],
        ]);
    }

    public function isActive(): bool
    {
        return $this->boolean('is_active');
    }
}
