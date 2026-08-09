<?php

namespace App\Http\Resources\API\V1\PaymentPlan;

use App\Http\Resources\BasePaginationResource;

class AdminPaymentPlanCollection extends BasePaginationResource
{
    public $collects = PaymentPlanResource::class;
}
