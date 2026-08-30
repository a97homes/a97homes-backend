<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\SubArea\DeleteSubAreaAction;
use App\Actions\SubArea\StoreSubAreaAction;
use App\Actions\SubArea\UpdateSubAreaAction;
use App\Enums\Role\UserRoleEnum;
use App\Filters\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\SubArea\StoreSubAreaRequest;
use App\Http\Requests\API\V1\Admin\SubArea\UpdateSubAreaMediaRequest;
use App\Http\Requests\API\V1\Admin\SubArea\UpdateSubAreaRequest;
use App\Http\Resources\SubArea\SubAreaCollection;
use App\Http\Resources\SubArea\SubAreaResource;
use App\Models\SubArea;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class SubAreaController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sub_areas.index']), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sub_areas.store']), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sub_areas.update']), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sub_areas.show']), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sub_areas.destroy']), only: ['destroy']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sub_areas.index']), only: ['dropdown']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sub_areas.media.update']), only: ['updateMedia']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sub_areas.media.destroy']), only: ['deleteMedia']),
        ];
    }

    public function index(): JsonResponse
    {
        $subAreas = QueryBuilder::for(SubArea::class)
            ->with(['area:id,name,country_id', 'area.country:id,name', 'media'])
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::custom('name', new NameFilter),
                AllowedFilter::custom('search', new NameFilter),
                AllowedFilter::exact('area_id'),
                AllowedFilter::scope('country_id'),
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
            ])
            ->macroPaginate();

        return $this->ok(data: new SubAreaCollection($subAreas));
    }

    public function store(StoreSubAreaRequest $request, StoreSubAreaAction $action): JsonResponse
    {
        $subArea = $action->execute($request->validated());

        return $this->ok(message: __('messages.sub_area_created_successfully'), data: SubAreaResource::make($subArea));
    }

    public function update(UpdateSubAreaRequest $request, SubArea $subArea, UpdateSubAreaAction $action): JsonResponse
    {
        $action->execute($subArea, $request->validated());

        return $this->ok(
            message: __('messages.sub_area_updated_successfully'),
            data: SubAreaResource::make($subArea)
        );
    }

    public function show(SubArea $subArea): JsonResponse
    {
        return $this->ok(data: SubAreaResource::make(
            $subArea->load(['area:id,name,country_id', 'area.country:id,name'])
        ));
    }

    public function destroy(SubArea $subArea, DeleteSubAreaAction $action): JsonResponse
    {
        $action->execute($subArea);

        return $this->ok(message: __('messages.sub_area_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $subAreas = SubArea::select('id', 'name')->get();

        return $this->ok(data: SubAreaResource::collection($subAreas));
    }

    public function updateMedia(UpdateSubAreaMediaRequest $request, SubArea $subArea): JsonResponse
    {
        $subArea->clearMediaCollection(SubArea::MEDIA_COLLECTION_IMAGE);
        $subArea->addMedia($request->file('image'))->toMediaCollection(SubArea::MEDIA_COLLECTION_IMAGE);

        return $this->ok(
            message: __('messages.sub_area_image_updated_successfully'),
            data: SubAreaResource::make($subArea->load('media')),
        );
    }

    public function deleteMedia(SubArea $subArea): JsonResponse
    {
        $subArea->clearMediaCollection(SubArea::MEDIA_COLLECTION_IMAGE);

        return $this->ok(message: __('messages.sub_area_image_deleted_successfully'));
    }
}
