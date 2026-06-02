<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Offer\StoreOfferRequest;
use App\Http\Requests\API\V1\Admin\Offer\UpdateOfferRequest;
use App\Http\Resources\API\V1\Offer\OfferCollection;
use App\Http\Resources\API\V1\Offer\OfferResource;
use App\Models\Offer;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class OfferController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_OFFERS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_OFFERS_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_OFFERS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_OFFERS_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_OFFERS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $offers = QueryBuilder::for(Offer::class)
            ->allowedFilters([
                AllowedFilter::exact('compound_id'),
                AllowedFilter::exact('is_active'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('monthly_payment'),
                AllowedSort::field('created_at'),
            ])
            ->with('compound:id,name')
            ->macroPaginate();

        return $this->ok(data: new OfferCollection($offers));
    }

    public function store(StoreOfferRequest $request): JsonResponse
    {
        $offer = Offer::create($request->validated());

        return $this->ok(
            message: __('messages.offer_created_successfully'),
            data: OfferResource::make($offer->load('compound:id,name')),
        );
    }

    public function show(Offer $offer): JsonResponse
    {
        return $this->ok(data: OfferResource::make($offer->load('compound:id,name')));
    }

    public function update(UpdateOfferRequest $request, Offer $offer): JsonResponse
    {
        $offer->update($request->validated());

        return $this->ok(
            message: __('messages.offer_updated_successfully'),
            data: OfferResource::make($offer->refresh()->load('compound:id,name')),
        );
    }

    public function destroy(Offer $offer): JsonResponse
    {
        $offer->delete();

        return $this->ok(message: __('messages.offer_deleted_successfully'));
    }
}
