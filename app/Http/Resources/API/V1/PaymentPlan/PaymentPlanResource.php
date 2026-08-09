<?php

namespace App\Http\Resources\API\V1\PaymentPlan;

use App\Http\Resources\API\V1\Compound\AdminCompoundResource;
use App\Models\PaymentPlan;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PaymentPlanResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var PaymentPlan $paymentPlan */
        $paymentPlan = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $paymentPlan->id),
            'compound' => AdminCompoundResource::make($this->whenLoaded('compound')),
            'type' => $this->whenHas('type', fn () => $paymentPlan->type),
            'installment_years' => $this->whenHas('installment_years', fn () => $paymentPlan->installment_years),
            'down_payment' => $this->whenHas('down_payment', fn () => $paymentPlan->down_payment),
            'monthly_payment' => $this->whenHas('monthly_payment', fn () => $paymentPlan->monthly_payment),
            'is_active' => $this->whenHas('is_active', fn () => $paymentPlan->is_active),
            'created_at' => $this->whenHas('created_at', fn () => $paymentPlan->created_at),
        ];
    }
}
