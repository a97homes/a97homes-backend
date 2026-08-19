<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Area;

use App\Models\Area;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAreaMediaRequest extends FormRequest
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
            'collection' => ['required', 'string', Rule::in(Area::MEDIA_COLLECTIONS)],
            'file' => ['required', 'file', 'mimes:jpg,jpeg,png,webp,svg', 'max:'.config('media-library.max_file_size')],
        ];
    }
}
