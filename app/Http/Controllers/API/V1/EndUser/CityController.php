<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\City\CityResource;
use App\Models\City;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CityController extends Controller
{
    public function popular(): JsonResponse
    {
        $cities = QueryBuilder::for(City::class)
            ->withCount('properties')
            ->with('state:id,name')
            ->whereHas('properties')
            ->defaultSort('-properties_count')
            ->allowedSorts([
                AllowedSort::field('properties_count'),
            ])->macroPaginate();

        return $this->ok(data: CityResource::collection($cities));
    }
}
