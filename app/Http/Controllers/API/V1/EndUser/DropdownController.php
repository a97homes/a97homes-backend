<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Enums\CompletionStatusEnum;
use App\Enums\SaleTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Attribute\FilterableAttributeResource;
use App\Http\Resources\API\V1\Compound\AdminCompoundResource;
use App\Http\Resources\API\V1\Developer\DeveloperResource;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeResource;
use App\Models\Attribute;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Offer;
use App\Models\Property;
use App\Models\PropertyType;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;

class DropdownController extends Controller
{
    public function developers(): JsonResponse
    {
        $developers = Developer::query()->active()->select('id', 'name')->get();

        return $this->ok(data: DeveloperResource::collection($developers));
    }

    public function compounds(): JsonResponse
    {
        $compounds = Compound::select('id', 'name')->get();

        return $this->ok(data: AdminCompoundResource::collection($compounds));
    }

    public function propertyTypes(): JsonResponse
    {
        $propertyTypes = PropertyType::query()->select('id', 'name', 'slug')->get();

        return $this->ok(data: PropertyTypeResource::collection($propertyTypes));
    }

    public function saleTypes(): JsonResponse
    {
        $saleTypes = array_map(fn ($e) => [
            'value' => $e->value,
            'label' => $e->name,
        ], SaleTypeEnum::cases());

        return $this->ok(data: $saleTypes);
    }

    public function priceRange(): JsonResponse
    {
        $priceRange = Property::query()->selectRaw('MIN(price) as min_price, MAX(price) as max_price')->first();

        return $this->ok(data: [
            'min' => (int) ($priceRange->min_price ?? 0),
            'max' => (int) ($priceRange->max_price ?? 0),
        ]);
    }

    public function areaRange(): JsonResponse
    {
        $areaRange = DB::table('attribute_property')
            ->join('attributes', 'attributes.id', '=', 'attribute_property.attribute_id')
            ->where('attributes.name->en', 'Area')
            ->selectRaw('MIN(CAST(attribute_property.value AS NUMERIC)) as min_area, MAX(CAST(attribute_property.value AS NUMERIC)) as max_area')
            ->first();

        return $this->ok(data: [
            'min' => (int) ($areaRange->min_area ?? 0),
            'max' => (int) ($areaRange->max_area ?? 0),
        ]);
    }

    public function deliveryDates(): JsonResponse
    {
        $deliveryYears = DB::table('compounds')
            ->whereNotNull('delivery_date')
            ->selectRaw('DISTINCT EXTRACT(YEAR FROM delivery_date) as year')
            ->orderBy('year')
            ->pluck('year')
            ->map(fn ($y) => (int) $y)
            ->values();

        $hasDelivered = DB::table('compounds')
            ->where('completion_status', CompletionStatusEnum::Completed->value)
            ->exists();

        return $this->ok(data: [
            'has_delivered' => $hasDelivered,
            'years' => $deliveryYears,
        ]);
    }

    public function paymentPlans(): JsonResponse
    {
        $installmentYears = Offer::query()
            ->where('is_active', true)
            ->distinct()
            ->orderBy('installment_years')
            ->pluck('installment_years');

        $downPaymentRange = Offer::query()
            ->where('is_active', true)
            ->selectRaw('MIN(down_payment_percentage) as min_down_payment, MAX(down_payment_percentage) as max_down_payment')
            ->first();

        $monthlyPaymentRange = Offer::query()
            ->where('is_active', true)
            ->selectRaw('MIN(monthly_payment) as min_monthly_payment, MAX(monthly_payment) as max_monthly_payment')
            ->first();

        return $this->ok(data: [
            'installment_years' => $installmentYears,
            'down_payment_range' => [
                'min' => (float) ($downPaymentRange->min_down_payment ?? 0),
                'max' => (float) ($downPaymentRange->max_down_payment ?? 0),
            ],
            'monthly_payment_range' => [
                'min' => (int) ($monthlyPaymentRange->min_monthly_payment ?? 0),
                'max' => (int) ($monthlyPaymentRange->max_monthly_payment ?? 0),
            ],
        ]);
    }

    public function attributes(): JsonResponse
    {
        $filterableAttributes = Attribute::query()
            ->where('is_filterable', true)
            ->with(['unit', 'activeOptions'])
            ->get();

        return $this->ok(data: FilterableAttributeResource::collection($filterableAttributes));
    }
}
