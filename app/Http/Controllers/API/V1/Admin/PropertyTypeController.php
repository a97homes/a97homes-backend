<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\PropertyType\DeletePropertyTypeAction;
use App\Actions\PropertyType\StorePropertyTypeAction;
use App\Actions\PropertyType\UpdatePropertyTypeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\PropertyType\StorePropertyTypeRequest;
use App\Http\Requests\API\V1\Admin\PropertyType\UpdatePropertyTypeRequest;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeCollection;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeResource;
use App\Models\PropertyType;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PropertyTypeController extends Controller
{
    public function index(): JsonResponse
    {
        $propertyTypes = QueryBuilder::for(PropertyType::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('id')
            ->allowedSorts([
                AllowedSort::field('-id'),
                AllowedSort::field('name'),
            ])
            ->macroPaginate();

        return $this->ok(data: new PropertyTypeCollection($propertyTypes));
    }

    public function store(StorePropertyTypeRequest $request, StorePropertyTypeAction $action): JsonResponse
    {
        $propertyType = $action->execute($request->validated());

        return $this->ok(message: __('messages.property_type_created_successfully'), data: PropertyTypeResource::make($propertyType));
    }

    public function update(UpdatePropertyTypeRequest $request, PropertyType $propertyType, UpdatePropertyTypeAction $action): JsonResponse
    {
        $action->execute($propertyType, $request->validated());

        return $this->ok(message: __('messages.property_type_updated_successfully'), data: PropertyTypeResource::make($propertyType));
    }

    public function show(PropertyType $propertyType): JsonResponse
    {
        return $this->ok(data: PropertyTypeResource::make($propertyType));
    }

    public function destroy(PropertyType $propertyType, DeletePropertyTypeAction $action): JsonResponse
    {
        $action->execute($propertyType);

        return $this->ok(message: __('messages.property_type_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $propertyTypes = PropertyType::select('id', 'name')->get();

        return $this->ok(data: PropertyTypeResource::collection($propertyTypes));
    }
}
