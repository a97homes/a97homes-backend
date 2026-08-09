<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Page\StorePageRequest;
use App\Http\Requests\API\V1\Admin\Page\UpdatePageRequest;
use App\Http\Resources\API\V1\Page\PageCollection;
use App\Http\Resources\API\V1\Page\PageResource;
use App\Models\Page;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PageController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PAGES_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PAGES_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PAGES_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PAGES_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PAGES_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $pages = QueryBuilder::for(Page::class)
            ->allowedFilters([
                AllowedFilter::exact('is_published'),
                AllowedFilter::partial('slug'),
            ])
            ->defaultSort('sort_order')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('sort_order'),
                AllowedSort::field('created_at'),
            ])
            ->macroPaginate();

        return $this->ok(data: new PageCollection($pages));
    }

    public function store(StorePageRequest $request): JsonResponse
    {
        $page = Page::create($request->validated());

        return $this->ok(
            message: __('messages.page_created_successfully'),
            data: PageResource::make($page),
        );
    }

    public function show(Page $page): JsonResponse
    {
        return $this->ok(data: PageResource::make($page));
    }

    public function update(UpdatePageRequest $request, Page $page): JsonResponse
    {
        $page->update($request->validated());

        return $this->ok(
            message: __('messages.page_updated_successfully'),
            data: PageResource::make($page->refresh()),
        );
    }

    public function destroy(Page $page): JsonResponse
    {
        $page->delete();

        return $this->ok(message: __('messages.page_deleted_successfully'));
    }
}
