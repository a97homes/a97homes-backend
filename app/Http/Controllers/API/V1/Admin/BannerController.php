<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Banner\DeleteBannerAction;
use App\Actions\Banner\StoreBannerAction;
use App\Actions\Banner\UpdateBannerAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Banner\StoreBannerRequest;
use App\Http\Requests\API\V1\Admin\Banner\UpdateBannerRequest;
use App\Http\Resources\API\V1\Banner\BannerCollection;
use App\Http\Resources\API\V1\Banner\BannerResource;
use App\Models\Banner;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class BannerController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.banners.index']), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.banners.store']), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.banners.show']), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.banners.update']), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.banners.destroy']), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $banners = QueryBuilder::for(Banner::class)
            ->allowedFilters([
                AllowedFilter::exact('is_active'),
            ])
            ->defaultSort('sort_order')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('sort_order'),
                AllowedSort::field('created_at'),
            ])
            ->with('media')
            ->macroPaginate();

        return $this->ok(data: new BannerCollection($banners));
    }

    public function store(StoreBannerRequest $request, StoreBannerAction $action): JsonResponse
    {
        $banner = $action->execute($request->validated());

        return $this->ok(
            message: __('messages.banner_created_successfully'),
            data: BannerResource::make($banner),
        );
    }

    public function show(Banner $banner): JsonResponse
    {
        return $this->ok(data: BannerResource::make($banner->load('media')));
    }

    public function update(UpdateBannerRequest $request, Banner $banner, UpdateBannerAction $action): JsonResponse
    {
        $action->execute($banner, $request->validated());

        return $this->ok(
            message: __('messages.banner_updated_successfully'),
            data: BannerResource::make($banner->load('media')),
        );
    }

    public function destroy(Banner $banner, DeleteBannerAction $action): JsonResponse
    {
        $action->execute($banner);

        return $this->ok(message: __('messages.banner_deleted_successfully'));
    }
}
