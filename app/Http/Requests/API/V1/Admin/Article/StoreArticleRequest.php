<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Article;

use App\Enums\ArticleTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class StoreArticleRequest extends FormRequest
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
            'slug' => ['required', 'string', 'max:255', 'alpha_dash', Rule::unique('articles', 'slug')],
            'type' => ['required', new Enum(ArticleTypeEnum::class)],
            'title' => ['required', 'array'],
            'title.ar' => ['required', 'string', 'max:255'],
            'title.en' => ['required', 'string', 'max:255'],
            'excerpt' => ['nullable', 'array'],
            'excerpt.ar' => ['nullable', 'string'],
            'excerpt.en' => ['nullable', 'string'],
            'body' => ['required', 'array'],
            'body.ar' => ['required', 'string'],
            'body.en' => ['required', 'string'],
            'author' => ['nullable', 'string', 'max:255'],
            'is_featured' => ['nullable', 'boolean'],
            'cover' => ['nullable', 'image', 'max:5120'],
        ];
    }
}
