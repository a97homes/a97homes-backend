<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Filters\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Property\ComparePropertiesRequest;
use App\Http\Resources\API\V1\Property\PropertyCollection;
use App\Http\Resources\API\V1\Property\PropertyCompareResource;
use App\Http\Resources\API\V1\Property\PropertyResource;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PropertyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = QueryBuilder::for(Property::class)
            ->with([
                'city:id,name,state_id',
                'city.state:id,name',
                'propertyType:id,name',
                'attributes' => fn ($q) => $q->with('unit'),
                'selectedOptions',
                'media',
            ])
            ->allowedFilters([
                AllowedFilter::custom('name', new NameFilter),
                AllowedFilter::exact('property_type_id'),
                AllowedFilter::exact('city_id'),
                AllowedFilter::exact('compound_id'),
                AllowedFilter::exact('status'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('created_at'),
            ]);

        $this->applyAttributeFilters($query, $request);
        $this->loadFavoritesForAuthUser($query);

        $properties = $query->macroPaginate();

        return $this->ok(data: new PropertyCollection($properties));
    }

    public function show(Property $property): JsonResponse
    {
        $property->load([
            'city:id,name,state_id',
            'city.state:id,name,country_id',
            'city.state.country:id,name',
            'propertyType:id,name',
            'attributes' => fn ($q) => $q->with('unit'),
            'selectedOptions.attribute',
            'compound',
            'media',
        ]);

        return $this->ok(data: PropertyResource::make($property));
    }

    public function compare(ComparePropertiesRequest $request): JsonResponse
    {
        $properties = Property::query()
            ->with([
                'city:id,name,state_id',
                'city.state:id,name',
                'propertyType:id,name',
                'attributes' => fn ($q) => $q->with('unit'),
                'selectedOptions.attribute',
                'compound:id,name,developer_id,delivery_date,completion_status',
                'compound.developer.media',
                'media',
            ])
            ->whereIn('id', $request->input('property_ids'))
            ->get();

        return $this->ok(data: PropertyCompareResource::collection($properties));
    }

    private function loadFavoritesForAuthUser(QueryBuilder $query): void
    {
        $userId = authUserId();

        if ($userId) {
            $query->withCount([
                'propertyFavorites as is_favorited' => function ($q) use ($userId) {
                    $q->where('user_id', $userId);
                },
            ]);
        }
    }

    private function applyAttributeFilters(QueryBuilder $query, Request $request): void
    {
        // Filter by price range: ?price_min=1000000&price_max=5000000
        $this->applyNumericAttributeRange($query, $request, 'price', 'Total Price');

        // Filter by area range: ?area_min=100&area_max=300
        $this->applyNumericAttributeRange($query, $request, 'area', 'Area');

        // Filter by bedrooms: ?bedrooms_min=2&bedrooms_max=4
        $this->applyNumericAttributeRange($query, $request, 'bedrooms', 'Number of Bedrooms');

        // Filter by bathrooms: ?bathrooms_min=1&bathrooms_max=3
        $this->applyNumericAttributeRange($query, $request, 'bathrooms', 'Number of Bathrooms');

        // Filter by floor: ?floor_min=1&floor_max=10
        $this->applyNumericAttributeRange($query, $request, 'floor', 'Floor Number');

        // Filter by select options: ?options[]=1&options[]=5&options[]=12
        if ($request->filled('options')) {
            $optionIds = (array) $request->input('options');

            $query->whereHas('selectedOptions', function ($q) use ($optionIds) {
                $q->whereIn('attribute_options.id', $optionIds);
            });
        }

        // Filter by boolean amenities: ?amenities[]=32&amenities[]=33
        if ($request->filled('amenities')) {
            $amenityIds = (array) $request->input('amenities');

            foreach ($amenityIds as $attributeId) {
                $query->whereHas('attributes', function ($q) use ($attributeId) {
                    $q->where('attributes.id', $attributeId)
                        ->where('attribute_property.value', '1');
                });
            }
        }
    }

    private function applyNumericAttributeRange(QueryBuilder $query, Request $request, string $param, string $attributeName): void
    {
        $min = $request->input("{$param}_min");
        $max = $request->input("{$param}_max");

        if ($min === null && $max === null) {
            return;
        }

        $query->whereHas('attributes', function ($q) use ($attributeName, $min, $max) {
            $q->where('attributes.name->en', $attributeName);

            if ($min !== null) {
                $q->whereRaw('CAST(attribute_property.value AS NUMERIC) >= ?', [(int) $min]);
            }

            if ($max !== null) {
                $q->whereRaw('CAST(attribute_property.value AS NUMERIC) <= ?', [(int) $max]);
            }
        });
    }
}
