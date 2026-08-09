<?php

declare(strict_types=1);

namespace App\Enums;

enum ArticleTypeEnum: string
{
    case Blog = 'blog';
    case Media = 'media';
    case News = 'news';
}
