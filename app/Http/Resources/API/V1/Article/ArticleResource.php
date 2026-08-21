<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Article;

use App\Models\Article;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ArticleResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Article $article */
        $article = $this->resource;

        return [
            'id' => $article->id,
            'slug' => $article->slug,
            'type' => $article->type?->value,
            'title' => $this->getTranslatableField($article, 'title'),
            'excerpt' => $this->getTranslatableField($article, 'excerpt'),
            'body' => $this->when(
                ! $request->boolean('summary', false),
                fn () => $this->getTranslatableField($article, 'body')
            ),
            'author' => $article->author,
            'published_at' => $article->published_at,
            'views_count' => $article->views_count,
            'is_featured' => $article->is_featured,
            'cover_image_url' => $article->getFirstMediaUrl(Article::MEDIA_COLLECTION_COVER) ?: null,
            'gallery' => $this->when(
                $article->relationLoaded('media'),
                fn () => $article->getMedia(Article::MEDIA_COLLECTION_GALLERY)->map(fn ($m) => [
                    'id' => $m->id,
                    'url' => $m->getFullUrl(),
                    'type' => $m->mime_type,
                ])
            ),
            'created_at' => $article->created_at,
            'updated_at' => $article->updated_at,
        ];
    }
}
