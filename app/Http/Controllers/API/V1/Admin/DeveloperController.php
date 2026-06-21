<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Developer\DeleteDeveloperAction;
use App\Actions\Developer\StoreDeveloperAction;
use App\Actions\Developer\UpdateDeveloperAction;
use App\Enums\Role\UserRoleEnum;
use App\Filters\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Developer\StoreDeveloperRequest;
use App\Http\Requests\API\V1\Admin\Developer\UpdateDeveloperRequest;
use App\Http\Resources\API\V1\Developer\DeveloperCollection;
use App\Http\Resources\API\V1\Developer\DeveloperResource;
use App\Models\Developer;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class DeveloperController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_DEVELOPERS_INDEX]), only: ['index', 'dropdown']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_DEVELOPERS_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_DEVELOPERS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_DEVELOPERS_UPDATE]), only: ['update', 'toggleActive']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_DEVELOPERS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $developers = QueryBuilder::for(Developer::class)
            ->allowedFilters([
                AllowedFilter::custom('name', new NameFilter), // use partial for search
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts(['id'])
            ->macroPaginate();

        return $this->ok(data: new DeveloperCollection($developers));
    }

    public function store(StoreDeveloperRequest $request, StoreDeveloperAction $action): JsonResponse
    {
        $developer = $action->execute($request->validated());

        return $this->ok(message: __('messages.developer_created_successfully'), data: DeveloperResource::make($developer));
    }

    public function show(Developer $developer): JsonResponse
    {
        return $this->ok(data: DeveloperResource::make($developer));
    }

    public function update(UpdateDeveloperRequest $request, Developer $developer, UpdateDeveloperAction $action): JsonResponse
    {
        $action->execute($developer, $request->validated());

        return $this->ok(message: __('messages.developer_updated_successfully'), data: DeveloperResource::make($developer));
    }

    public function destroy(Developer $developer, DeleteDeveloperAction $action): JsonResponse
    {
        $action->execute($developer);

        return $this->ok(message: __('messages.developer_deleted_successfully'));
    }

    public function toggleActive(Developer $developer): JsonResponse
    {
        $developer->update(['is_active' => ! $developer->is_active]);

        return $this->ok(
            message: __($developer->is_active ? 'messages.developer_activated' : 'messages.developer_deactivated'),
            data: DeveloperResource::make($developer->refresh()),
        );
    }

    public function dropdown(): JsonResponse
    {
        $developers = Developer::select('id', 'name')->get();

        return $this->ok(data: DeveloperResource::collection($developers));
    }
}
