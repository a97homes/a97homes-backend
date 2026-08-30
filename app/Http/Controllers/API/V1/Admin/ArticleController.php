<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Article\DeleteArticleAction;
use App\Actions\Article\StoreArticleAction;
use App\Actions\Article\UpdateArticleAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Article\StoreArticleRequest;
use App\Http\Requests\API\V1\Admin\Article\UpdateArticleRequest;
use App\Http\Resources\API\V1\Article\ArticleCollection;
use App\Http\Resources\API\V1\Article\ArticleResource;
use App\Models\Article;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ArticleController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.articles.index']), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.articles.store']), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.articles.show']), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.articles.update']), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.articles.destroy']), only: ['destroy']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.articles.update']), only: ['togglePublish']),
        ];
    }

    public function index(): JsonResponse
    {
        $articles = QueryBuilder::for(Article::query()->with('media'))
            ->allowedFilters([
                AllowedFilter::exact('type'),
                AllowedFilter::exact('is_featured'),
                AllowedFilter::callback('published', fn ($query, $value) => $value
                    ? $query->whereNotNull('published_at')->where('published_at', '<=', now())
                    : $query->where(fn ($q) => $q->whereNull('published_at')->orWhere('published_at', '>', now()))
                ),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('published_at'),
                AllowedSort::field('views_count'),
            ])
            ->macroPaginate();

        return $this->ok(data: new ArticleCollection($articles));
    }

    public function store(StoreArticleRequest $request, StoreArticleAction $action): JsonResponse
    {
        $data = $request->validated();
        $cover = $request->file('cover');
        unset($data['cover']);

        $article = $action->execute($data, $cover);

        return $this->ok(
            message: __('messages.article_created_successfully'),
            data: ArticleResource::make($article->load('media')),
        );
    }

    public function show(Article $article): JsonResponse
    {
        return $this->ok(data: ArticleResource::make($article->load('media')));
    }

    public function update(UpdateArticleRequest $request, Article $article, UpdateArticleAction $action): JsonResponse
    {
        $data = $request->validated();
        $cover = $request->file('cover');
        unset($data['cover']);

        $action->execute($article, $data, $cover);

        return $this->ok(
            message: __('messages.article_updated_successfully'),
            data: ArticleResource::make($article->load('media')),
        );
    }

    public function destroy(Article $article, DeleteArticleAction $action): JsonResponse
    {
        $action->execute($article);

        return $this->ok(message: __('messages.article_deleted_successfully'));
    }

    /**
     * Toggle publication by setting/clearing published_at.
     */
    public function togglePublish(Article $article): JsonResponse
    {
        $article->update([
            'published_at' => $article->published_at === null ? now() : null,
        ]);

        return $this->ok(
            message: __($article->published_at === null ? 'messages.article_unpublished' : 'messages.article_published'),
            data: ArticleResource::make($article->load('media')),
        );
    }
}
