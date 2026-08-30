<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Area\DeleteAreaAction;
use App\Actions\Area\StoreAreaAction;
use App\Actions\Area\UpdateAreaAction;
use App\Enums\Role\UserRoleEnum;
use App\Filters\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Area\StoreAreaRequest;
use App\Http\Requests\API\V1\Admin\Area\UpdateAreaMediaRequest;
use App\Http\Requests\API\V1\Admin\Area\UpdateAreaRequest;
use App\Http\Resources\Area\AreaCollection;
use App\Http\Resources\Area\AreaResource;
use App\Models\Area;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class AreaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.areas.index']), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.areas.store']), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.areas.update']), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.areas.show']), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.areas.destroy']), only: ['destroy']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.areas.index']), only: ['dropdown']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.areas.media.update']), only: ['updateMedia']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.areas.media.destroy']), only: ['deleteMedia']),
        ];
    }

    public function index(): JsonResponse
    {
        $areas = QueryBuilder::for(Area::class)
            ->with(['country:id,name', 'media'])
            ->withCount('subAreas')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::custom('name', new NameFilter),
                AllowedFilter::custom('search', new NameFilter),
                AllowedFilter::exact('country_id'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
                AllowedFilter::scope('updated_from'),
                AllowedFilter::scope('updated_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
                AllowedSort::field('created_at'),
                AllowedSort::field('updated_at'),
                AllowedSort::field('sub_areas_count'),
            ])
            ->macroPaginate();

        return $this->ok(data: new AreaCollection($areas));
    }

    public function store(StoreAreaRequest $request, StoreAreaAction $action): JsonResponse
    {
        $area = $action->execute($request->validated());

        return $this->ok(message: __('messages.area_created_successfully'), data: AreaResource::make($area));
    }

    public function update(UpdateAreaRequest $request, Area $area, UpdateAreaAction $action): JsonResponse
    {
        $action->execute($area, $request->validated());

        return $this->ok(message: __('messages.area_updated_successfully'), data: AreaResource::make($area));
    }

    public function show(Area $area): JsonResponse
    {
        $area->load(['country:id,name', 'media'])->loadCount('subAreas');

        return $this->ok(data: AreaResource::make($area));
    }

    public function destroy(Area $area, DeleteAreaAction $action): JsonResponse
    {
        $action->execute($area);

        return $this->ok(message: __('messages.area_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $areas = Area::select('id', 'name')->get();

        return $this->ok(data: AreaResource::collection($areas));
    }

    public function updateMedia(UpdateAreaMediaRequest $request, Area $area): JsonResponse
    {
        $collection = $request->string('collection')->toString();

        $area->clearMediaCollection($collection);
        $area->addMedia($request->file('file'))->toMediaCollection($collection);

        return $this->ok(
            message: __('messages.area_media_updated_successfully'),
            data: AreaResource::make($area->load('media')),
        );
    }

    public function deleteMedia(Area $area, string $collection): JsonResponse
    {
        abort_unless(in_array($collection, Area::MEDIA_COLLECTIONS, true), 404);

        $area->clearMediaCollection($collection);

        return $this->ok(message: __('messages.area_media_deleted_successfully'));
    }
}
