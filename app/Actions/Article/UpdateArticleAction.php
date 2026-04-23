<?php

declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Media\AddMediaAction;
use App\Models\Article;

class UpdateArticleAction
{
    public function __construct(private readonly AddMediaAction $addMedia) {}

    public function execute(Article $article, array $data, ?\Illuminate\Http\UploadedFile $cover = null): Article
    {
        $article->update($data);

        if ($cover !== null) {
            $article->clearMediaCollection(Article::MEDIA_COLLECTION_COVER);
            $this->addMedia->execute($article, ['file' => $cover], Article::MEDIA_COLLECTION_COVER);
        }

        return $article->refresh();
    }
}
