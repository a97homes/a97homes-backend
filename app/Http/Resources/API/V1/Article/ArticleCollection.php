<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Article;

use App\Http\Resources\BasePaginationResource;

class ArticleCollection extends BasePaginationResource
{
    public $collects = ArticleResource::class;
}
