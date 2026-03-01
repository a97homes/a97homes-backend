<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Filters\CompoundAttributeFilter;
use App\Filters\NameFilter;
use App\Filters\PriceRangeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Compound\CompareCompoundsRequest;
use App\Http\Resources\API\V1\Compound\CompoundCollection;
use App\Http\Resources\API\V1\Compound\CompoundCompareResource;
use App\Http\Resources\API\V1\Compound\CompoundResource;
use App\Models\Compound;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CompoundController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = QueryBuilder::for(Compound::class)
            ->with([
                'developer:id,name',
                'developer.media',
                'city:id,name,state_id',
                'city.state:id,name',
                'properties:id,compound_id,property_type_id,price,resale_price',
                'properties.propertyType:id,name',
                'activeDiscount',
            ])
            ->withMin('properties', 'price')
            ->withMin('properties', 'resale_price')
            ->withMax('properties', 'price')
            ->allowedFilters([
                AllowedFilter::custom('name', new NameFilter),
                AllowedFilter::exact('city_id'),
                AllowedFilter::exact('developer_id'),
                AllowedFilter::exact('completion_status'),
                AllowedFilter::custom('price', new PriceRangeFilter),
                AllowedFilter::custom('bedrooms', new CompoundAttributeFilter('number-of-bedrooms')),
                AllowedFilter::custom('bathrooms', new CompoundAttributeFilter('number-of-bathrooms')),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('created_at'),
                AllowedSort::field('name'),
            ]);

        $query->withFavoritesForUser(authUserId());

        $compounds = $query->macroPaginate();

        return $this->ok(data: new CompoundCollection($compounds));
    }

    public function show(Compound $compound): JsonResponse
    {
        $compound->load([
            'developer.media',
            'city:id,name,state_id',
            'city.state:id,name',
            'properties:id,compound_id,property_type_id,price,resale_price',
            'properties.propertyType:id,name',
            'properties.media',
            'activeDiscount',
            'media',
        ]);

        $compound->loadMin('properties', 'price');
        $compound->loadMin('properties', 'resale_price');
        $compound->loadMax('properties', 'price');

        $userId = authUserId();
        if ($userId) {
            $compound->is_favorited = $compound->favorites()
                ->where('user_id', $userId)->exists();
        }

        return $this->ok(data: CompoundResource::make($compound));
    }

    public function compare(CompareCompoundsRequest $request): JsonResponse
    {
        $compounds = Compound::query()
            ->with([
                'developer.media',
                'city:id,name,state_id',
                'city.state:id,name',
                'properties:id,compound_id,property_type_id,price,resale_price',
                'properties.propertyType:id,name',
                'activeDiscount',
            ])
            ->withMin('properties', 'price')
            ->withMin('properties', 'resale_price')
            ->withMax('properties', 'price')
            ->whereIn('id', $request->input('compound_ids'))
            ->get();

        return $this->ok(data: CompoundCompareResource::collection($compounds));
    }
}
