<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Enums\SellUnitStatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\SellUnit\SellUnitCollection;
use App\Http\Resources\API\V1\SellUnit\SellUnitResource;
use App\Models\SellUnit;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class SellUnitController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sell_units.index']), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sell_units.show']), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sell_units.update']), only: ['approve']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sell_units.update']), only: ['reject']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.sell_units.destroy']), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $sellUnits = QueryBuilder::for(SellUnit::class)
            ->allowedFilters([
                AllowedFilter::exact('status'),
                AllowedFilter::exact('sub_area_id'),
                AllowedFilter::exact('property_type_id'),
                AllowedFilter::exact('compound_id'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('created_at'),
            ])
            ->with(['subArea:id,name', 'propertyType:id,name', 'compound:id,name'])
            ->macroPaginate();

        return $this->ok(data: new SellUnitCollection($sellUnits));
    }

    public function show(SellUnit $sellUnit): JsonResponse
    {
        return $this->ok(data: SellUnitResource::make(
            $sellUnit->load(['subArea:id,name', 'propertyType:id,name', 'compound:id,name'])
        ));
    }

    public function approve(SellUnit $sellUnit): JsonResponse
    {
        $sellUnit->update(['status' => SellUnitStatusEnum::APPROVED]);

        return $this->ok(
            message: __('messages.sell_unit_approved_successfully'),
            data: SellUnitResource::make($sellUnit->refresh()),
        );
    }

    public function reject(SellUnit $sellUnit): JsonResponse
    {
        $sellUnit->update(['status' => SellUnitStatusEnum::REJECTED]);

        return $this->ok(
            message: __('messages.sell_unit_rejected_successfully'),
            data: SellUnitResource::make($sellUnit->refresh()),
        );
    }

    public function destroy(SellUnit $sellUnit): JsonResponse
    {
        $sellUnit->delete();

        return $this->ok(message: __('messages.sell_unit_deleted_successfully'));
    }
}
