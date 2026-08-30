<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\PropertyType\DeletePropertyTypeAction;
use App\Actions\PropertyType\StorePropertyTypeAction;
use App\Actions\PropertyType\UpdatePropertyTypeAction;
use App\Enums\Role\UserRoleEnum;
use App\Filters\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\PropertyType\StorePropertyTypeRequest;
use App\Http\Requests\API\V1\Admin\PropertyType\UpdatePropertyTypeRequest;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeCollection;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeResource;
use App\Models\PropertyType;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PropertyTypeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.property_types.index']), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.property_types.store']), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.property_types.update']), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.property_types.show']), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.property_types.destroy']), only: ['destroy']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.property_types.index']), only: ['dropdown']),
        ];
    }

    public function index(): JsonResponse
    {
        $propertyTypes = QueryBuilder::for(PropertyType::class)
            ->with('attributes:id,name')
            ->allowedFilters([
                AllowedFilter::custom('name', new NameFilter),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
            ])
            ->macroPaginate();

        return $this->ok(data: new PropertyTypeCollection($propertyTypes));
    }

    public function store(StorePropertyTypeRequest $request, StorePropertyTypeAction $action): JsonResponse
    {
        $propertyType = $action->execute($request->validated());

        return $this->ok(message: __('messages.property_type_created_successfully'), data: PropertyTypeResource::make($propertyType));
    }

    public function update(UpdatePropertyTypeRequest $request, PropertyType $propertyType, UpdatePropertyTypeAction $action): JsonResponse
    {
        $action->execute($propertyType, $request->validated());

        return $this->ok(message: __('messages.property_type_updated_successfully'), data: PropertyTypeResource::make($propertyType));
    }

    public function show(PropertyType $propertyType): JsonResponse
    {
        $propertyType->load('attributes:id,name');

        return $this->ok(data: PropertyTypeResource::make($propertyType));
    }

    public function destroy(PropertyType $propertyType, DeletePropertyTypeAction $action): JsonResponse
    {
        $action->execute($propertyType);

        return $this->ok(message: __('messages.property_type_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $propertyTypes = PropertyType::select('id', 'name')->get();

        return $this->ok(data: PropertyTypeResource::collection($propertyTypes));
    }
}
