<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\EndUser;

use App\Enums\SaleTypeEnum;
use App\Filters\CompoundAttributeFilter;
use App\Filters\NameFilter;
use App\Filters\PriceRangeFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Compound\CompoundCollection;
use App\Http\Resources\API\V1\Faq\FaqResource;
use App\Http\Resources\API\V1\Offer\OfferCollection;
use App\Http\Resources\City\CityResource;
use App\Models\City;
use App\Models\Compound;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
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

    public function dropdown(): JsonResponse
    {
        $cities = City::select('id', 'name')->get();

        return $this->ok(data: CityResource::collection($cities));
    }

    /**
     * City (area) detail page header: description, cover, coords,
     * units and compounds counts.
     */
    public function show(City $city): JsonResponse
    {
        $city->load([
            'state:id,name,country_id',
            'state.country:id,name',
            'media',
        ]);

        $city->loadCount([
            'properties as units_count',
            'compounds as compounds_count',
        ]);

        return $this->ok(data: CityResource::make($city));
    }

    /**
     * Active offers belonging to compounds in this city.
     */
    public function offers(City $city): JsonResponse
    {
        $offers = Offer::query()
            ->active()
            ->with([
                'compound:id,name,developer_id,city_id',
                'compound.developer:id,name',
                'compound.developer.media',
            ])
            ->whereHas('compound', fn ($q) => $q->where('city_id', $city->id))
            ->latest()
            ->macroPaginate();

        return $this->ok(data: new OfferCollection($offers));
    }

    /**
     * Compounds in this city with sort/filter and sale_type tab support.
     *
     * Query params:
     *   sale_type = resale | developer | all (defaults to all)
     */
    public function compounds(Request $request, City $city): JsonResponse
    {
        $saleType = $request->input('sale_type', 'all');

        $query = QueryBuilder::for(Compound::class)
            ->where('city_id', $city->id)
            ->with([
                'developer:id,name',
                'developer.media',
                'city:id,name,state_id',
                'city.state:id,name',
                'properties:id,compound_id,property_type_id,price,resale_price,sale_type',
                'properties.propertyType:id,name',
                'activeDiscount',
                'media',
            ])
            ->withMin('properties', 'price')
            ->withMin('properties', 'resale_price')
            ->withMax('properties', 'price')
            ->allowedFilters([
                AllowedFilter::custom('name', new NameFilter),
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

        $this->applySaleTypeTab($query, $saleType);

        $query->withFavoritesForUser(authUserId());

        $compounds = $query->macroPaginate();

        return $this->ok(data: new CompoundCollection($compounds));
    }

    /**
     * FAQs attached to this city.
     */
    public function faqs(City $city): JsonResponse
    {
        $faqs = $city->activeFaqs()->get();

        return $this->ok(data: FaqResource::collection($faqs));
    }

    private function applySaleTypeTab($query, string $saleType): void
    {
        $normalized = strtolower($saleType);

        if ($normalized === SaleTypeEnum::Resale->value) {
            $query->whereHas('properties', fn ($q) => $q->where('sale_type', SaleTypeEnum::Resale->value));

            return;
        }

        if ($normalized === SaleTypeEnum::Developer->value) {
            $query->whereHas('properties', fn ($q) => $q->where('sale_type', SaleTypeEnum::Developer->value));
        }
    }
}
