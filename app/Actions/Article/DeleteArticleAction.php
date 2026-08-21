<?php

declare(strict_types=1);

namespace App\Actions\Article;

use App\Models\Article;

class DeleteArticleAction
{
    public function execute(Article $article): void
    {
        $article->delete();
    }
}
