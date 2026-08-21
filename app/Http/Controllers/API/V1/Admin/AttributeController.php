<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Attribute\DeleteAttributeAction;
use App\Actions\Attribute\StoreAttributeAction;
use App\Actions\Attribute\UpdateAttributeAction;
use App\Enums\Role\UserRoleEnum;
use App\Filters\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Attribute\StoreAttributeRequest;
use App\Http\Requests\API\V1\Admin\Attribute\UpdateAttributeRequest;
use App\Http\Resources\API\V1\Attribute\AttributeCollection;
use App\Http\Resources\API\V1\Attribute\AttributeResource;
use App\Models\Attribute;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class AttributeController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_ATTRIBUTES_INDEX]), only: ['index', 'dropdown']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_ATTRIBUTES_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_ATTRIBUTES_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_ATTRIBUTES_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_ATTRIBUTES_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $attributes = QueryBuilder::for(Attribute::class)
            ->with('unit:id,name')
            ->allowedFilters([
                AllowedFilter::custom('name', new NameFilter),
                AllowedFilter::exact('type'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
                AllowedSort::field('type'),
            ])
            ->macroPaginate();

        return $this->ok(data: new AttributeCollection($attributes));
    }

    public function store(StoreAttributeRequest $request, StoreAttributeAction $action): JsonResponse
    {
        $attribute = $action->execute($request->validated());

        return $this->ok(
            message: __('messages.attribute_created_successfully'),
            data: AttributeResource::make($attribute)
        );
    }

    public function update(UpdateAttributeRequest $request, Attribute $attribute, UpdateAttributeAction $action): JsonResponse
    {
        $action->execute($attribute, $request->validated());

        return $this->ok(message: __('messages.attribute_updated_successfully'), data: AttributeResource::make($attribute));
    }

    public function show(Attribute $attribute): JsonResponse
    {
        $attribute->load('unit:id,name');

        return $this->ok(data: AttributeResource::make($attribute));
    }

    public function destroy(Attribute $attribute, DeleteAttributeAction $action): JsonResponse
    {
        $action->execute($attribute);

        return $this->ok(message: __('messages.attribute_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $attributes = Attribute::select('id', 'name')->get();

        return $this->ok(data: AttributeResource::collection($attributes));
    }
}
