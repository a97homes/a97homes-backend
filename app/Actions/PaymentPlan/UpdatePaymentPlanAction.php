<?php

namespace App\Actions\PaymentPlan;

use App\Models\PaymentPlan;

class UpdatePaymentPlanAction
{
    public function execute(PaymentPlan $paymentPlan, array $data): PaymentPlan
    {
        $paymentPlan->update($data);

        return $paymentPlan;
    }
}
