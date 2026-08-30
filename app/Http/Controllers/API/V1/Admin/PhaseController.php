<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Phase\StorePhaseRequest;
use App\Http\Requests\API\V1\Admin\Phase\UpdatePhaseRequest;
use App\Http\Resources\API\V1\Phase\PhaseCollection;
use App\Http\Resources\API\V1\Phase\PhaseResource;
use App\Models\Phase;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PhaseController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.phases.index']), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.phases.store']), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.phases.show']), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.phases.update']), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.phases.destroy']), only: ['destroy']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.phases.index']), only: ['dropdown']),
        ];
    }

    public function index(): JsonResponse
    {
        $phases = QueryBuilder::for(Phase::class)
            ->allowedFilters([
                AllowedFilter::exact('compound_id'),
                AllowedFilter::exact('completion_status'),
            ])
            ->defaultSort('sort_order')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('sort_order'),
                AllowedSort::field('delivery_date'),
                AllowedSort::field('created_at'),
            ])
            ->with('compound:id,name')
            ->macroPaginate();

        return $this->ok(data: new PhaseCollection($phases));
    }

    public function store(StorePhaseRequest $request): JsonResponse
    {
        $phase = Phase::create($request->validated());

        return $this->ok(
            message: __('messages.phase_created_successfully'),
            data: PhaseResource::make($phase),
        );
    }

    public function show(Phase $phase): JsonResponse
    {
        return $this->ok(data: PhaseResource::make($phase->load('compound:id,name')));
    }

    public function update(UpdatePhaseRequest $request, Phase $phase): JsonResponse
    {
        $phase->update($request->validated());

        return $this->ok(
            message: __('messages.phase_updated_successfully'),
            data: PhaseResource::make($phase->refresh()),
        );
    }

    public function destroy(Phase $phase): JsonResponse
    {
        $phase->delete();

        return $this->ok(message: __('messages.phase_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $phases = Phase::query()
            ->with('compound:id,name')
            ->orderBy('compound_id')
            ->orderBy('sort_order')
            ->get();

        return $this->ok(data: PhaseResource::collection($phases));
    }
}
