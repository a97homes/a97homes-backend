<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Banner\BannerResource;
use App\Http\Resources\API\V1\Compound\CompoundCollection;
use App\Http\Resources\API\V1\Property\PropertyCollection;
use App\Http\Resources\City\CityResource;
use App\Models\Banner;
use App\Models\City;
use App\Models\Compound;
use App\Models\Property;
use Illuminate\Http\JsonResponse;

class HomeController extends Controller
{
    /**
     * Compounds with active payment plan offers.
     */
    public function offers(): JsonResponse
    {
        $userId = authUserId();

        $compounds = Compound::query()
            ->with([
                'developer:id,name',
                'developer.media',
                'city:id,name,state_id',
                'city.state:id,name',
                'activeOffers',
                'activeDiscount',
                'media',
                'properties:id,compound_id,property_type_id,price,resale_price',
                'properties.propertyType:id,name',
            ])
            ->whereHas('activeOffers')
            ->withMin('properties', 'price')
            ->withMax('properties', 'price')
            ->latest()
            ->macroPaginate();

        if ($userId) {
            $compounds->getCollection()->each(function ($compound) use ($userId) {
                $compound->is_favorited = $compound->favorites()
                    ->where('user_id', $userId)->exists();
            });
        }

        return $this->ok(data: new \App\Http\Resources\API\V1\Compound\CompoundCollection($compounds));
    }

    /**
     * Active promotional banners.
     */
    public function banners(): JsonResponse
    {
        $banners = Banner::query()
            ->active()
            ->with('media')
            ->orderBy('sort_order')
            ->get();

        return $this->ok(data: BannerResource::collection($banners));
    }

    /**
     * Latest compounds as projects with media.
     */
    public function latestProjects(): JsonResponse
    {
        $compounds = Compound::query()
            ->with([
                'developer:id,name',
                'developer.media',
                'media',
            ])
            ->has('media')
            ->latest()
            ->macroPaginate();

        return $this->ok(data: new CompoundCollection($compounds));
    }

    /**
     * Popular cities/areas with property counts and images.
     */
    public function popularAreas(): JsonResponse
    {
        $cities = City::query()
            ->withCount('properties')
            ->with(['state:id,name', 'media'])
            ->whereHas('properties')
            ->orderByDesc('properties_count')
            ->macroPaginate();

        return $this->ok(data: CityResource::collection($cities));
    }

    /**
     * Featured/most searched compounds.
     */
    public function featuredCompounds(): JsonResponse
    {
        $compounds = Compound::select('id', 'name')
            ->with([
                'media',
            ])
            ->withCount('properties')
            ->where('is_featured', true)
            ->latest()
            ->macroPaginate();

        return $this->ok(data: new CompoundCollection($compounds));
    }

    /**
     * Featured properties ("We chose you").
     */
    public function featuredProperties(): JsonResponse
    {
        $userId = authUserId();

        $query = Property::query()
            ->with([
                'city:id,name,state_id',
                'city.state:id,name',
                'propertyType:id,name',
                'compound:id,name,developer_id,delivery_date,completion_status',
                'compound.developer:id,name',
                'compound.developer.media',
                'compound.activeDiscount',
                'attributes' => fn ($q) => $q->with('unit'),
                'selectedOptions',
                'media',
            ])
            ->where('is_featured', true)
            ->where('status', 'active');

        if ($userId) {
            $query->withCount([
                'propertyFavorites as is_favorited' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                },
            ]);
        }

        $properties = $query->latest()->macroPaginate();

        return $this->ok(data: new PropertyCollection($properties));
    }
}
