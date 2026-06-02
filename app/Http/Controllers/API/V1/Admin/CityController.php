<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\City\DeleteCityAction;
use App\Actions\City\StoreCityAction;
use App\Actions\City\UpdateCityAction;
use App\Enums\Role\UserRoleEnum;
use App\Filters\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\City\StoreCityRequest;
use App\Http\Requests\API\V1\Admin\City\UpdateCityMediaRequest;
use App\Http\Requests\API\V1\Admin\City\UpdateCityRequest;
use App\Http\Resources\City\CityCollection;
use App\Http\Resources\City\CityResource;
use App\Models\City;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CityController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CITIES_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CITIES_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CITIES_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CITIES_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CITIES_DESTROY]), only: ['destroy']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CITIES_MEDIA_UPDATE]), only: ['updateMedia']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CITIES_MEDIA_DESTROY]), only: ['deleteMedia']),
        ];
    }

    public function index(): JsonResponse
    {
        $cities = QueryBuilder::for(City::class)
            ->allowedFilters([
                AllowedFilter::custom('name', new NameFilter),
                AllowedFilter::exact('state_id'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
            ])
            ->macroPaginate();

        return $this->ok(data: new CityCollection($cities));
    }

    public function store(StoreCityRequest $request, StoreCityAction $action): JsonResponse
    {
        $city = $action->execute($request->validated());

        return $this->ok(message: __('messages.city_created_successfully'), data: CityResource::make($city));
    }

    public function update(UpdateCityRequest $request, City $city, UpdateCityAction $action): JsonResponse
    {
        $action->execute($city, $request->validated());

        return $this->ok(
            message: __('messages.city_updated_successfully'),
            data: CityResource::make($city)
        );
    }

    public function show(City $city): JsonResponse
    {
        return $this->ok(data: CityResource::make(
            $city->load(['state:id,name,country_id', 'state.country:id,name'])
        ));
    }

    public function destroy(City $city, DeleteCityAction $action): JsonResponse
    {
        $action->execute($city);

        return $this->ok(message: __('messages.city_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $cities = City::select('id', 'name')->get();

        return $this->ok(data: CityResource::collection($cities));
    }

    public function updateMedia(UpdateCityMediaRequest $request, City $city): JsonResponse
    {
        $city->clearMediaCollection(City::MEDIA_COLLECTION_IMAGE);
        $city->addMedia($request->file('image'))->toMediaCollection(City::MEDIA_COLLECTION_IMAGE);

        return $this->ok(
            message: __('messages.city_image_updated_successfully'),
            data: CityResource::make($city->load('media')),
        );
    }

    public function deleteMedia(City $city): JsonResponse
    {
        $city->clearMediaCollection(City::MEDIA_COLLECTION_IMAGE);

        return $this->ok(message: __('messages.city_image_deleted_successfully'));
    }
}
