<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Phase\DeletePhaseAction;
use App\Actions\Phase\StorePhaseAction;
use App\Actions\Phase\UpdatePhaseAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Phase\StorePhaseRequest;
use App\Http\Requests\API\V1\Admin\Phase\UpdatePhaseRequest;
use App\Http\Resources\API\V1\Phase\PhaseCollection;
use App\Http\Resources\API\V1\Phase\PhaseResource;
use App\Models\Phase;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PhaseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {

        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PHASES_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PHASES_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PHASES_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PHASES_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PHASES_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $phases = QueryBuilder::for(Phase::class)
            ->allowedFilters([
                AllowedFilter::exact('project_id'),
                AllowedFilter::exact('property_type_id'),
                AllowedFilter::partial('name'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts(['id'])
            ->macroPaginate();

        return $this->ok(data: new PhaseCollection($phases));
    }

    public function store(StorePhaseRequest $request, StorePhaseAction $action): JsonResponse
    {
        $phase = $action->execute($request->validated());

        return $this->ok(__('messages.phase_created_successfully'), PhaseResource::make($phase));
    }

    public function show(Phase $phase): JsonResponse
    {
        return $this->ok(data: PhaseResource::make($phase->load(['project:id,name,developer_id', 'project.developer:id,name'])));
    }

    public function update(UpdatePhaseRequest $request, Phase $phase, UpdatePhaseAction $action): JsonResponse
    {
        $action->execute($phase, $request->validated());

        return $this->ok(__('messages.phase_updated_successfully'), PhaseResource::make($phase));
    }

    public function destroy(Phase $phase, DeletePhaseAction $action): JsonResponse
    {
        $action->execute($phase);

        return $this->ok(message: __('messages.phase_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $phases = Phase::select('id', 'name')->get();

        return $this->ok(data: PhaseResource::collection($phases));
    }
}
