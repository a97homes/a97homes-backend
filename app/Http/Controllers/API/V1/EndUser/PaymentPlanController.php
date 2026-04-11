<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\PaymentPlan\PaymentPlanResource;
use App\Models\PaymentPlan;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PaymentPlanController extends Controller
{
    public function index(): JsonResponse
    {
        $paymentPlans = QueryBuilder::for(PaymentPlan::class)
            ->where('is_active', true)
            ->with('compound:id,name')
            ->allowedFilters([
                AllowedFilter::exact('compound_id'),
                AllowedFilter::exact('type'),
            ])
            ->defaultSort('-id')
            ->allowedSorts(['id', 'installment_years', 'down_payment', 'monthly_payment'])
            ->macroPaginate();

        return $this->ok(data: PaymentPlanResource::collection($paymentPlans));
    }
}
