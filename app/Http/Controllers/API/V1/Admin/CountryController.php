<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Country\DeleteCountryAction;
use App\Actions\Country\StoreCountryAction;
use App\Actions\Country\UpdateCountryAction;
use App\Enums\Role\UserRoleEnum;
use App\Filters\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Country\StoreCountryRequest;
use App\Http\Requests\API\V1\Admin\Country\UpdateCountryRequest;
use App\Http\Resources\API\V1\Country\CountryCollection;
use App\Http\Resources\API\V1\Country\CountryResource;
use App\Models\Country;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CountryController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'country.index']), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'country.store']), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'country.update']), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'country.show']), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'country.destroy']), only: ['destroy']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'country.index']), only: ['dropdown']),
        ];
    }

    public function index(): JsonResponse
    {
        $countries = QueryBuilder::for(Country::class)
            ->withCount('areas')
            ->allowedFilters([
                AllowedFilter::exact('id'),
                AllowedFilter::custom('name', new NameFilter),
                AllowedFilter::custom('search', new NameFilter),
                AllowedFilter::exact('code'),
                AllowedFilter::exact('phone_code'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
                AllowedFilter::scope('updated_from'),
                AllowedFilter::scope('updated_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
                AllowedSort::field('code'),
                AllowedSort::field('created_at'),
                AllowedSort::field('updated_at'),
                AllowedSort::field('areas_count'),
            ])
            ->macroPaginate();

        return $this->ok(data: new CountryCollection($countries));
    }

    public function store(StoreCountryRequest $request, StoreCountryAction $action): JsonResponse
    {
        $country = $action->execute($request->validated());

        return $this->ok(message: __('messages.country_created_successfully'), data: CountryResource::make($country));
    }

    public function update(UpdateCountryRequest $request, Country $country, UpdateCountryAction $action): JsonResponse
    {
        $action->execute($country, $request->validated());

        return $this->ok(message: __('messages.country_updated_successfully'), data: CountryResource::make($country));
    }

    public function show(Country $country): JsonResponse
    {
        return $this->ok(data: CountryResource::make($country));
    }

    public function destroy(Country $country, DeleteCountryAction $action): JsonResponse
    {
        $action->execute($country);

        return $this->ok(message: __('messages.country_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $countries = Country::select('id', 'name')->get();

        return $this->ok(data: CountryResource::collection($countries));
    }
}
