<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Discount\StoreDiscountRequest;
use App\Http\Requests\API\V1\Admin\Discount\UpdateDiscountRequest;
use App\Http\Resources\API\V1\Discount\DiscountCollection;
use App\Http\Resources\API\V1\Discount\DiscountResource;
use App\Models\Discount;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class DiscountController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.discounts.index']), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.discounts.store']), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.discounts.show']), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.discounts.update']), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.discounts.destroy']), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $discounts = QueryBuilder::for(Discount::class)
            ->allowedFilters([
                AllowedFilter::exact('compound_id'),
                AllowedFilter::exact('is_active'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('percentage'),
                AllowedSort::field('start_date'),
                AllowedSort::field('end_date'),
            ])
            ->with('compound:id,name')
            ->macroPaginate();

        return $this->ok(data: new DiscountCollection($discounts));
    }

    public function store(StoreDiscountRequest $request): JsonResponse
    {
        $discount = Discount::create($request->validated());

        return $this->ok(
            message: __('messages.discount_created_successfully'),
            data: DiscountResource::make($discount->load('compound:id,name')),
        );
    }

    public function show(Discount $discount): JsonResponse
    {
        return $this->ok(data: DiscountResource::make($discount->load('compound:id,name')));
    }

    public function update(UpdateDiscountRequest $request, Discount $discount): JsonResponse
    {
        $discount->update($request->validated());

        return $this->ok(
            message: __('messages.discount_updated_successfully'),
            data: DiscountResource::make($discount->refresh()->load('compound:id,name')),
        );
    }

    public function destroy(Discount $discount): JsonResponse
    {
        $discount->delete();

        return $this->ok(message: __('messages.discount_deleted_successfully'));
    }
}
