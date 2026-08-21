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
use App\Http\Resources\SubArea\SubAreaResource;
use App\Models\SubArea;
use App\Models\Compound;
use App\Models\Offer;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class SubAreaController extends Controller
{
    public function popular(): JsonResponse
    {
        $subAreas = QueryBuilder::for(SubArea::class)
            ->withCount('properties')
            ->with('area:id,name')
            ->whereHas('properties')
            ->defaultSort('-properties_count')
            ->allowedSorts([
                AllowedSort::field('properties_count'),
            ])->macroPaginate();

        return $this->ok(data: SubAreaResource::collection($subAreas));
    }

    public function dropdown(): JsonResponse
    {
        $subAreas = SubArea::select('id', 'name')->get();

        return $this->ok(data: SubAreaResource::collection($subAreas));
    }

    /**
     * SubArea (area) detail page header: description, cover, coords,
     * units and compounds counts.
     */
    public function show(SubArea $subArea): JsonResponse
    {
        $subArea->load([
            'area:id,name,country_id',
            'area.country:id,name',
            'media',
        ]);

        $subArea->loadCount([
            'properties as units_count',
            'compounds as compounds_count',
        ]);

        return $this->ok(data: SubAreaResource::make($subArea));
    }

    /**
     * Active offers belonging to compounds in this subArea.
     */
    public function offers(SubArea $subArea): JsonResponse
    {
        $offers = Offer::query()
            ->active()
            ->with([
                'compound:id,name,developer_id,sub_area_id',
                'compound.developer:id,name',
                'compound.developer.media',
            ])
            ->whereHas('compound', fn ($q) => $q->where('sub_area_id', $subArea->id))
            ->latest()
            ->macroPaginate();

        return $this->ok(data: new OfferCollection($offers));
    }

    /**
     * Compounds in this subArea with sort/filter and sale_type tab support.
     *
     * Query params:
     *   sale_type = resale | developer | all (defaults to all)
     */
    public function compounds(Request $request, SubArea $subArea): JsonResponse
    {
        $saleType = $request->input('sale_type', 'all');

        $query = QueryBuilder::for(Compound::class)
            ->where('sub_area_id', $subArea->id)
            ->with([
                'developer:id,name',
                'developer.media',
                'subArea:id,name,area_id',
                'subArea.area:id,name',
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
     * FAQs attached to this subArea.
     */
    public function faqs(SubArea $subArea): JsonResponse
    {
        $faqs = $subArea->activeFaqs()->get();

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
