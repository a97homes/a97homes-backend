<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Unit\DeleteUnitAction;
use App\Actions\Unit\StoreUnitAction;
use App\Actions\Unit\UpdateUnitAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Unit\StoreUnitRequest;
use App\Http\Requests\API\V1\Admin\Unit\UpdateUnitRequest;
use App\Http\Resources\API\V1\Unit\UnitCollection;
use App\Http\Resources\API\V1\Unit\UnitResource;
use App\Models\Unit;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class UnitController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_UNITS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_UNITS_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_UNITS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_UNITS_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_UNITS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $units = QueryBuilder::for(Unit::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('symbol'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
                AllowedSort::field('symbol'),
            ])
            ->macroPaginate();

        return $this->ok(data: new UnitCollection($units));
    }

    public function store(StoreUnitRequest $request, StoreUnitAction $action): JsonResponse
    {
        $unit = $action->execute($request->validated());

        return $this->ok(message: __('messages.unit_created_successfully'), data: UnitResource::make($unit)
        );
    }

    public function update(UpdateUnitRequest $request, Unit $unit, UpdateUnitAction $action): JsonResponse
    {
        $action->execute($unit, $request->validated());

        return $this->ok(message: __('messages.unit_updated_successfully'), data: UnitResource::make($unit));
    }

    public function show(Unit $unit): JsonResponse
    {
        return $this->ok(data: UnitResource::make($unit));
    }

    public function destroy(Unit $unit, DeleteUnitAction $action): JsonResponse
    {
        $action->execute($unit);

        return $this->ok(message: __('messages.unit_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $units = Unit::select('id', 'name', 'symbol')->get();

        return $this->ok(data: UnitResource::collection($units));
    }
}
