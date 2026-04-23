<?php

declare(strict_types=1);

namespace App\Http\Requests\API\V1\Admin\Article;

use App\Enums\ArticleTypeEnum;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class UpdateArticleRequest extends FormRequest
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
        $articleId = $this->route('article')?->id;

        return [
            'slug' => ['sometimes', 'string', 'max:255', 'alpha_dash', Rule::unique('articles', 'slug')->ignore($articleId)],
            'type' => ['sometimes', new Enum(ArticleTypeEnum::class)],
            'title' => ['sometimes', 'array'],
            'title.ar' => ['required_with:title', 'string', 'max:255'],
            'title.en' => ['required_with:title', 'string', 'max:255'],
            'excerpt' => ['sometimes', 'array'],
            'excerpt.ar' => ['nullable', 'string'],
            'excerpt.en' => ['nullable', 'string'],
            'body' => ['sometimes', 'array'],
            'body.ar' => ['required_with:body', 'string'],
            'body.en' => ['required_with:body', 'string'],
            'author' => ['sometimes', 'nullable', 'string', 'max:255'],
            'published_at' => ['sometimes', 'nullable', 'date'],
            'is_featured' => ['sometimes', 'boolean'],
            'cover' => ['sometimes', 'image', 'max:5120'],
        ];
    }
}
