<?php

declare(strict_types=1);

namespace App\Actions\Article;

use App\Actions\Media\AddMediaAction;
use App\Models\Article;

class StoreArticleAction
{
    public function __construct(private readonly AddMediaAction $addMedia) {}

    public function execute(array $data, ?\Illuminate\Http\UploadedFile $cover = null): Article
    {
        $article = Article::create($data);

        if ($cover !== null) {
            $this->addMedia->execute($article, ['file' => $cover], Article::MEDIA_COLLECTION_COVER);
        }

        return $article;
    }
}
