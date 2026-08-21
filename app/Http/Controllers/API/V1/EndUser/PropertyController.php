<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Filters\DeliveryDateFilter;
use App\Filters\NameFilter;
use App\Filters\PaymentPlanFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Property\ComparePropertiesRequest;
use App\Http\Resources\API\V1\Property\PropertyCollection;
use App\Http\Resources\API\V1\Property\PropertyCompareResource;
use App\Http\Resources\API\V1\Property\PropertyMapResource;
use App\Http\Resources\API\V1\Property\PropertyResource;
use App\Models\Property;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PropertyController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = $this->filteredQuery($request)
            ->with([
                'subArea:id,name,area_id',
                'subArea.area:id,name',
                'propertyType:id,name',
                'compound:id,name,developer_id,delivery_date,completion_status',
                'compound.developer:id,name,is_active',
                'compound.developer.media',
                'compound.activeOffers',
                'compound.activeDiscount',
                'attributes' => fn ($q) => $q->with('unit'),
                'selectedOptions',
                'media',
                'phones',
                'whatsappNumbers',
            ]);

        $this->loadFavoritesForAuthUser($query);

        $properties = $query->macroPaginate();

        return $this->ok(data: new PropertyCollection($properties));
    }

    /**
     * Non-paginated, lightweight list for map rendering. Same filters as the
     * index; only properties that have coordinates are returned.
     */
    public function map(Request $request): JsonResponse
    {
        $query = $this->filteredQuery($request)
            ->with([
                'subArea:id,name,area_id',
                'propertyType:id,name',
                'compound:id,name,developer_id',
                'compound.developer:id,name',
                'media',
                'phones',
                'whatsappNumbers',
            ])
            ->whereNotNull('latitude')
            ->whereNotNull('longitude');

        $this->loadFavoritesForAuthUser($query);

        $properties = $query->get();

        return $this->ok(data: PropertyMapResource::collection($properties));
    }

    private function filteredQuery(Request $request): QueryBuilder
    {
        $query = QueryBuilder::for(Property::class)
            ->allowedFilters([
                AllowedFilter::custom('name', new NameFilter),
                AllowedFilter::exact('property_type_id'),
                AllowedFilter::exact('sub_area_id'),
                AllowedFilter::exact('subArea.area_id'),
                AllowedFilter::exact('compound_id'),
                AllowedFilter::exact('status'),
                AllowedFilter::exact('sale_type'),
                AllowedFilter::exact('is_featured'),
                AllowedFilter::exact('compound.developer_id'),
                AllowedFilter::exact('compound.completion_status'),
                AllowedFilter::custom('delivery_date', new DeliveryDateFilter),
                AllowedFilter::callback('finishing_type', function (Builder $query, $value): void {
                    $values = array_values(array_filter(is_array($value) ? $value : [$value], fn ($v) => $v !== null && $v !== ''));
                    if ($values === []) {
                        return;
                    }
                    $query->whereHas('selectedOptions', function (Builder $q) use ($values): void {
                        $q->whereIn('attribute_options.id', $values)
                            ->whereHas('attribute', fn (Builder $attr) => $attr->where('slug', 'finishing-type'));
                    });
                }),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::callback('created_at', function (Builder $q, bool $descending): void {
                    $direction = $descending ? 'desc' : 'asc';
                    $q->orderBy('created_at', $direction)->orderBy('id', $direction);
                }),
                AllowedSort::field('price'),
            ]);

        $this->applyAttributeFilters($query, $request);
        PaymentPlanFilter::apply($query->getEloquentBuilder(), $request->only([
            'down_payment_min', 'down_payment_max',
            'monthly_payment_min', 'monthly_payment_max',
            'installment_years',
        ]));

        return $query;
    }

    public function show(Property $property): JsonResponse
    {
        $property->load([
            'subArea:id,name,area_id',
            'subArea.area:id,name,country_id',
            'subArea.area.country:id,name',
            'propertyType:id,name',
            'attributes' => fn ($q) => $q->with('unit'),
            'selectedOptions.attribute',
            'compound',
            'compound.developer:id,name',
            'compound.developer.media',
            'compound.activeOffers',
            'compound.activeDiscount',
            'compound.activePaymentPlans',
            'compound.media',
            'media',
            'phones',
            'whatsappNumbers',
        ]);

        return $this->ok(data: PropertyResource::make($property));
    }

    public function compare(ComparePropertiesRequest $request): JsonResponse
    {
        $properties = Property::query()
            ->with([
                'subArea:id,name,area_id',
                'subArea.area:id,name',
                'propertyType:id,name',
                'attributes' => fn ($q) => $q->with('unit'),
                'selectedOptions.attribute',
                'compound:id,name,developer_id,delivery_date,completion_status',
                'compound.developer.media',
                'media',
                'phones',
                'whatsappNumbers',
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
        // Filter by price range on the property price column: ?price_min=1000000&price_max=5000000
        $this->applyDirectPriceRange($query, $request);

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

    private function applyDirectPriceRange(QueryBuilder $query, Request $request): void
    {
        $min = $request->input('price_min');
        $max = $request->input('price_max');

        if ($min !== null) {
            $query->where('price', '>=', (int) $min);
        }

        if ($max !== null) {
            $query->where('price', '<=', (int) $max);
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
