<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Sorts\CreatedAtSort;
use App\Enums\Role\UserRoleEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;
use Spatie\QueryBuilder\AllowedFilter;
use App\Permissions\PermissionRegistry;
use Spatie\Permission\Models\Permission;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Routing\Controllers\HasMiddleware;
use App\Actions\Permission\DeletePermissionAction;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use App\Http\Resources\API\V1\Admin\Permission\PermissionResource;
use App\Http\Resources\API\V1\Admin\Permission\PermissionCollection;
use App\Http\Requests\API\V1\Admin\Permission\DeletePermissionRequest;

class PermissionController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PERMISSIONS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PERMISSIONS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PERMISSIONS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $permissions = QueryBuilder::for(Permission::class)

            ->allowedSorts([
                AllowedSort::custom('latest', new CreatedAtSort),
                AllowedSort::custom('oldest', new CreatedAtSort),
                AllowedSort::custom('default', new CreatedAtSort),
            ])
              ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
             // ->withCount('users')
            ->macroPaginate();

        return $this->ok(data: new PermissionCollection($permissions));
    }

    public function show(Permission $permission): JsonResponse
    {
        return $this->ok(data: PermissionResource::make($permission));
    }

    public function destroy(DeletePermissionRequest $request, Permission $permission, DeletePermissionAction $action): JsonResponse
    {
        $action->execute($permission);

        return $this->ok(message: __('messages.permissions_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $permissions = Permission::query()->select(['id', 'name'])->get();

        return $this->ok(data: PermissionResource::collection($permissions));
    }
}
