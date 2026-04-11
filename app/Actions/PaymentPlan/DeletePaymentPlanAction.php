<?php

namespace App\Actions\PaymentPlan;

use App\Models\PaymentPlan;

class DeletePaymentPlanAction
{
    public function execute(PaymentPlan $paymentPlan): bool
    {
        return $paymentPlan->delete();
    }
}
