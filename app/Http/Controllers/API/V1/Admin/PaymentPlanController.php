<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\PaymentPlan\DeletePaymentPlanAction;
use App\Actions\PaymentPlan\StorePaymentPlanAction;
use App\Actions\PaymentPlan\UpdatePaymentPlanAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\PaymentPlan\StorePaymentPlanRequest;
use App\Http\Requests\API\V1\Admin\PaymentPlan\UpdatePaymentPlanRequest;
use App\Http\Resources\API\V1\PaymentPlan\AdminPaymentPlanCollection;
use App\Http\Resources\API\V1\PaymentPlan\PaymentPlanResource;
use App\Models\PaymentPlan;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class PaymentPlanController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.payment_plans.index']), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.payment_plans.store']), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.payment_plans.show']), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.payment_plans.update']), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, 'admin.payment_plans.destroy']), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $paymentPlans = QueryBuilder::for(PaymentPlan::class)
            ->allowedFilters([
                AllowedFilter::exact('compound_id'),
                AllowedFilter::exact('type'),
                AllowedFilter::exact('is_active'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts(['id', 'installment_years', 'down_payment', 'monthly_payment'])
            ->macroPaginate();

        return $this->ok(data: new AdminPaymentPlanCollection($paymentPlans));
    }

    public function store(StorePaymentPlanRequest $request, StorePaymentPlanAction $action): JsonResponse
    {
        $paymentPlan = $action->execute($request->validated());

        return $this->ok(message: __('messages.payment_plan_created_successfully'), data: PaymentPlanResource::make($paymentPlan));
    }

    public function show(PaymentPlan $paymentPlan): JsonResponse
    {
        return $this->ok(data: PaymentPlanResource::make($paymentPlan->load('compound:id,name')));
    }

    public function update(UpdatePaymentPlanRequest $request, PaymentPlan $paymentPlan, UpdatePaymentPlanAction $action): JsonResponse
    {
        $action->execute($paymentPlan, $request->validated());

        return $this->ok(message: __('messages.payment_plan_updated_successfully'), data: PaymentPlanResource::make($paymentPlan));
    }

    public function destroy(PaymentPlan $paymentPlan, DeletePaymentPlanAction $action): JsonResponse
    {
        $action->execute($paymentPlan);

        return $this->ok(message: __('messages.payment_plan_deleted_successfully'));
    }
}
