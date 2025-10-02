<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\City\DeleteCityAction;
use App\Actions\City\StoreCityAction;
use App\Actions\City\UpdateCityAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\City\StoreCityRequest;
use App\Http\Requests\API\V1\Admin\City\UpdateCityRequest;
use App\Http\Resources\City\CityCollection;
use App\Http\Resources\City\CityResource;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CityController extends Controller
{
    public function index(): JsonResponse
    {
        $cities = QueryBuilder::for(City::class)
            ->with('state.country')
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::exact('state_id'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
            ])
            ->macroPaginate();

        return $this->ok(data: new CityCollection($cities));
    }

    public function store(StoreCityRequest $request, StoreCityAction $action): JsonResponse
    {
        $city = $action->execute($request->validated());

        return $this->ok(
            message: __('messages.city_created_successfully'),
            data: CityResource::make($city)
        );
    }

    public function update(UpdateCityRequest $request, City $city, UpdateCityAction $action): JsonResponse
    {
        $action->execute($city, $request->validated());

        return $this->ok(message: __('messages.city_updated_successfully'), data: CityResource::make($city)
        );
    }

    public function show(City $city): JsonResponse
    {
        return $this->ok(data: CityResource::make($city));
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
}
