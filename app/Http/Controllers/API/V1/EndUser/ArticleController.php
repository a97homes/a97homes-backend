<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\EndUser;

use App\Enums\ArticleTypeEnum;
use App\Filters\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Article\ArticleCollection;
use App\Http\Resources\API\V1\Article\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ArticleController extends Controller
{
    /**
     * Paginated list of published articles.
     *
     * Query params:
     *   filter[type]        = blog | media | news
     *   filter[is_featured] = 1 | 0
     *   filter[title]       = free-text search (Arabic-aware)
     *   sort                = published_at | -published_at | views_count | ...
     */
    public function index(): JsonResponse
    {
        $articles = QueryBuilder::for(Article::query()->published()->with('media'))
            ->allowedFilters([
                AllowedFilter::exact('type'),
                AllowedFilter::exact('is_featured'),
                AllowedFilter::custom('title', new NameFilter),
            ])
            ->defaultSort('-published_at')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('published_at'),
                AllowedSort::field('views_count'),
            ])
            ->macroPaginate();

        return $this->ok(data: new ArticleCollection($articles));
    }

    /**
     * Short list of featured published articles for home / sidebar widgets.
     */
    public function featured(): JsonResponse
    {
        $articles = Article::query()
            ->published()
            ->where('is_featured', true)
            ->with('media')
            ->latest('published_at')
            ->limit(6)
            ->get();

        return $this->ok(data: ArticleResource::collection($articles));
    }

    /**
     * Show a single published article by slug. Increments the views
     * counter atomically.
     */
    public function show(string $slug): JsonResponse
    {
        $article = Article::query()
            ->published()
            ->where('slug', $slug)
            ->with('media')
            ->firstOrFail();

        $article->increment('views_count');

        return $this->ok(data: ArticleResource::make($article->refresh()->load('media')));
    }

    /**
     * Dropdown helper that lists available article types.
     */
    public function types(): JsonResponse
    {
        return $this->ok(data: collect(ArticleTypeEnum::cases())->map(fn (ArticleTypeEnum $c) => [
            'value' => $c->value,
            'label' => __('articles.types.'.$c->value),
        ]));
    }
}
