<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Property\DeletePropertyAction;
use App\Actions\Property\StorePropertyAction;
use App\Actions\Property\UpdatePropertyAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Property\StorePropertyRequest;
use App\Http\Requests\API\V1\Admin\Property\UpdatePropertyRequest;
use App\Http\Resources\API\V1\Property\PropertyCollection;
use App\Http\Resources\API\V1\Property\PropertyResource;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PropertyController extends Controller
{
    public function index(): JsonResponse
    {
        $properties = QueryBuilder::for(Property::class)
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

        return $this->ok(data: new PropertyCollection($properties));
    }

    public function store(StorePropertyRequest $request, StorePropertyAction $action): JsonResponse
    {
        $property = $action->execute($request->validated());

        return $this->ok(message: __('messages.property_created_successfully'), data: PropertyResource::make($property));
    }

    public function update(UpdatePropertyRequest $request, Property $property, UpdatePropertyAction $action): JsonResponse
    {
        $action->execute($property, $request->validated());

        return $this->ok(message: __('messages.property_updated_successfully'), data: PropertyResource::make($property));
    }

    public function show(Property $property): JsonResponse
    {
        return $this->ok(data: PropertyResource::make($property));
    }

    public function destroy(Property $property, DeletePropertyAction $action): JsonResponse
    {
        $action->execute($property);

        return $this->ok(message: __('messages.property_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $properties = Property::select('id', 'name')->get();

        return $this->ok(data: PropertyResource::collection($properties));
    }
}
