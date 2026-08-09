<?php

namespace App\Actions\PaymentPlan;

use App\Models\PaymentPlan;

class StorePaymentPlanAction
{
    public function execute(array $data): PaymentPlan
    {
        return PaymentPlan::create($data);
    }
}
