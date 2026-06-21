<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Filters\CompoundAttributeFilter;
use App\Filters\CompoundDeliveryDateFilter;
use App\Filters\NameFilter;
use App\Filters\PaymentPlanFilter;
use App\Filters\PriceRangeFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Compound\CompareCompoundsRequest;
use App\Http\Requests\API\V1\EndUser\Compound\StoreCompoundReviewRequest;
use App\Http\Resources\API\V1\Compound\AdminCompoundResource;
use App\Http\Resources\API\V1\Compound\CompoundCollection;
use App\Http\Resources\API\V1\Compound\CompoundCompareResource;
use App\Http\Resources\API\V1\Compound\CompoundResource;
use App\Http\Resources\API\V1\CompoundReview\CompoundReviewCollection;
use App\Http\Resources\API\V1\CompoundReview\CompoundReviewResource;
use App\Models\Compound;
use App\Models\CompoundReview;
use Illuminate\Database\Eloquent\Builder;
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
                'developer:id,name,is_active',
                'developer.media',
                'city:id,name,state_id',
                'city.state:id,name',
                'properties:id,compound_id,property_type_id,price,resale_price',
                'properties.propertyType:id,name',
                'activeDiscount',
                'media',
            ])
            ->withMin('properties', 'price')
            ->withMin('properties', 'resale_price')
            ->withMax('properties', 'price')
            ->allowedFilters([
                AllowedFilter::custom('name', new NameFilter),
                AllowedFilter::exact('city_id'),
                AllowedFilter::exact('city.state_id'),
                AllowedFilter::exact('developer_id'),
                AllowedFilter::exact('completion_status'),
                AllowedFilter::exact('is_featured'),
                AllowedFilter::callback('property_type_id', function (Builder $query, $value): void {
                    $values = is_array($value) ? $value : [$value];
                    $query->whereHas('properties', fn (Builder $q) => $q->whereIn('property_type_id', $values));
                }),
                AllowedFilter::callback('status', function (Builder $query, $value): void {
                    $values = is_array($value) ? $value : [$value];
                    $query->whereHas('properties', fn (Builder $q) => $q->whereIn('status', $values));
                }),
                AllowedFilter::callback('sale_type', function (Builder $query, $value): void {
                    $values = is_array($value) ? $value : [$value];
                    $query->whereHas('properties', fn (Builder $q) => $q->whereIn('sale_type', $values));
                }),
                AllowedFilter::custom('price', new PriceRangeFilter),
                AllowedFilter::custom('delivery_date', new CompoundDeliveryDateFilter),
                AllowedFilter::callback('finishing_type', function (Builder $query, $value): void {
                    $values = array_values(array_filter(is_array($value) ? $value : [$value], fn ($v) => $v !== null && $v !== ''));
                    if ($values === []) {
                        return;
                    }
                    $query->whereHas('properties', function (Builder $pq) use ($values): void {
                        $pq->whereHas('selectedOptions', function (Builder $q) use ($values): void {
                            $q->whereIn('attribute_options.id', $values)
                                ->whereHas('attribute', fn (Builder $attr) => $attr->where('slug', 'finishing-type'));
                        });
                    });
                }),
                AllowedFilter::custom('bedrooms', new CompoundAttributeFilter('number-of-bedrooms')),
                AllowedFilter::custom('bathrooms', new CompoundAttributeFilter('number-of-bathrooms')),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('created_at'),
                AllowedSort::field('name'),
            ]);

        $this->applyAttributeFilters($query, $request);

        PaymentPlanFilter::apply(
            $query->getEloquentBuilder(),
            $request->only([
                'down_payment_min', 'down_payment_max',
                'monthly_payment_min', 'monthly_payment_max',
                'installment_years',
            ]),
            'activeOffers',
        );

        $query->withFavoritesForUser(authUserId());

        $compounds = $query->macroPaginate();

        return $this->ok(data: new CompoundCollection($compounds));
    }

    public function show(Compound $compound): JsonResponse
    {
        $userId = authUserId();

        $compound->load([
            'developer.media',
            'city:id,name,state_id',
            'city.state:id,name',
            'properties' => function ($q) use ($userId): void {
                if ($userId) {
                    $q->withCount([
                        'propertyFavorites as is_favorited' => fn (Builder $fav) => $fav->where('user_id', $userId),
                    ]);
                }
            },
            'properties.propertyType:id,name',
            'properties.attributes' => fn ($q) => $q->with('unit'),
            'properties.selectedOptions.attribute',
            'properties.media',
            'activeDiscount',
            'activeOffers',
            'activePaymentPlans',
            'media',
        ]);

        $compound->loadMin('properties', 'price');
        $compound->loadMin('properties', 'resale_price');
        $compound->loadMax('properties', 'price');
        $compound->loadCount('properties');
        $compound->loadCount('reviews');
        $compound->loadAvg('reviews', 'overall_rating');

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
                'media',
            ])
            ->withMin('properties', 'price')
            ->withMin('properties', 'resale_price')
            ->withMax('properties', 'price')
            ->whereIn('id', $request->input('compound_ids'))
            ->get();

        return $this->ok(data: CompoundCompareResource::collection($compounds));
    }

    public function dropdown(): JsonResponse
    {
        $compounds = Compound::select('id', 'name')->get();

        return $this->ok(data: AdminCompoundResource::collection($compounds));
    }

    /**
     * Paginated list of reviews for a compound, newest first.
     */
    public function reviews(Compound $compound): JsonResponse
    {
        $reviews = $compound->reviews()
            ->with('user')
            ->latest()
            ->macroPaginate();

        return $this->ok(data: new CompoundReviewCollection($reviews));
    }

    /**
     * Submit a review for a compound. Each user may submit at most one
     * review per compound — re-posting updates the existing row.
     */
    public function storeReview(StoreCompoundReviewRequest $request, Compound $compound): JsonResponse
    {
        $userId = authUserId();

        $review = CompoundReview::query()->updateOrCreate(
            ['compound_id' => $compound->id, 'user_id' => $userId],
            $request->validated(),
        );

        return $this->ok(
            message: __('messages.review_created_successfully'),
            data: CompoundReviewResource::make($review->load('user')),
        );
    }

    /**
     * Up to 6 similar compounds — same city first, then same developer,
     * excluding the current compound.
     */
    public function similar(Compound $compound): JsonResponse
    {
        $compounds = Compound::query()
            ->with(['developer:id,name,is_active', 'developer.media', 'city:id,name,state_id', 'city.state:id,name', 'media'])
            ->withMin('properties', 'price')
            ->withMax('properties', 'price')
            ->where('id', '!=', $compound->id)
            ->where(function ($q) use ($compound): void {
                $q->where('city_id', $compound->city_id)
                    ->orWhere('developer_id', $compound->developer_id);
            })
            ->orderByRaw('CASE WHEN city_id = ? THEN 0 ELSE 1 END', [$compound->city_id])
            ->limit(6)
            ->get();

        return $this->ok(data: CompoundResource::collection($compounds));
    }

    private function applyAttributeFilters(QueryBuilder $query, Request $request): void
    {
        // Filter by price range on related properties' price column: ?price_min=1000000&price_max=5000000
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

            $query->whereHas('properties', function (Builder $q) use ($optionIds): void {
                $q->whereHas('selectedOptions', function (Builder $inner) use ($optionIds): void {
                    $inner->whereIn('attribute_options.id', $optionIds);
                });
            });
        }

        // Filter by boolean amenities: ?amenities[]=32&amenities[]=33
        if ($request->filled('amenities')) {
            $amenityIds = (array) $request->input('amenities');

            foreach ($amenityIds as $attributeId) {
                $query->whereHas('properties', function (Builder $q) use ($attributeId): void {
                    $q->whereHas('attributes', function (Builder $attrQ) use ($attributeId): void {
                        $attrQ->where('attributes.id', $attributeId)
                            ->where('attribute_property.value', '1');
                    });
                });
            }
        }
    }

    private function applyDirectPriceRange(QueryBuilder $query, Request $request): void
    {
        $min = $request->input('price_min');
        $max = $request->input('price_max');

        if ($min === null && $max === null) {
            return;
        }

        $query->whereHas('properties', function (Builder $q) use ($min, $max): void {
            if ($min !== null) {
                $q->where('price', '>=', (int) $min);
            }

            if ($max !== null) {
                $q->where('price', '<=', (int) $max);
            }
        });
    }

    private function applyNumericAttributeRange(QueryBuilder $query, Request $request, string $param, string $attributeName): void
    {
        $min = $request->input("{$param}_min");
        $max = $request->input("{$param}_max");

        if ($min === null && $max === null) {
            return;
        }

        $query->whereHas('properties', function (Builder $q) use ($attributeName, $min, $max): void {
            $q->whereHas('attributes', function (Builder $attrQ) use ($attributeName, $min, $max): void {
                $attrQ->where('attributes.name->en', $attributeName);

                if ($min !== null) {
                    $attrQ->whereRaw('CAST(attribute_property.value AS NUMERIC) >= ?', [(int) $min]);
                }

                if ($max !== null) {
                    $attrQ->whereRaw('CAST(attribute_property.value AS NUMERIC) <= ?', [(int) $max]);
                }
            });
        });
    }
}
