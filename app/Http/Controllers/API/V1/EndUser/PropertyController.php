<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
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
                AllowedFilter::partial('name'), // TODO:: NameFilter
                AllowedFilter::exact('attributes.id'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
            ])
            ->macroPaginate();

        return $this->ok(data: new PropertyCollection($properties));
    }

    public function show(Property $property): JsonResponse
    {
        $property->load(['city:id,name,state_id', 'city.state:id,name,country_id', 'city.state.country:id,name', 'attributes:name,id']);

        return $this->ok(data: PropertyResource::make($property));
    }
}
