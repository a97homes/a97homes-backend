<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Faq\DeleteFaqAction;
use App\Actions\Faq\StoreFaqAction;
use App\Actions\Faq\UpdateFaqAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Faq\StoreFaqRequest;
use App\Http\Requests\API\V1\Admin\Faq\UpdateFaqRequest;
use App\Http\Resources\API\V1\Faq\FaqResource;
use App\Models\City;
use App\Models\Compound;
use App\Models\Faq;
use App\Permissions\PermissionRegistry;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class FaqController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_FAQS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_FAQS_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_FAQS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_FAQS_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_FAQS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $faqs = QueryBuilder::for(Faq::class)
            ->with(['faqable' => fn (MorphTo $morphTo) => $morphTo->morphWith($this->faqableEagerLoads())])
            ->allowedFilters([
                AllowedFilter::exact('faqable_id'),
                AllowedFilter::exact('faqable_type'),
                AllowedFilter::exact('is_active'),
            ])
            ->defaultSort('sort_order')
            ->allowedSorts(['id', 'sort_order', 'created_at'])
            ->macroPaginate();

        return $this->ok(data: FaqResource::collection($faqs));
    }

    public function store(StoreFaqRequest $request, StoreFaqAction $action): JsonResponse
    {
        $data = $request->validated();
        $data['faqable_type'] = $request->resolvedFaqableType();

        $faq = $action->execute($data);

        return $this->ok(message: __('messages.faq_created_successfully'), data: FaqResource::make($faq));
    }

    public function show(Faq $faq): JsonResponse
    {
        $faq->load(['faqable' => fn (MorphTo $morphTo) => $morphTo->morphWith($this->faqableEagerLoads())]);

        return $this->ok(data: FaqResource::make($faq));
    }

    /**
     * Per-type eager loads for the polymorphic faqable owner.
     *
     * @return array<class-string, array<int, string>>
     */
    private function faqableEagerLoads(): array
    {
        return [
            Compound::class => ['developer:id,name', 'city:id,name,state_id', 'city.state:id,name'],
            City::class => ['state:id,name'],
        ];
    }

    public function update(UpdateFaqRequest $request, Faq $faq, UpdateFaqAction $action): JsonResponse
    {
        $action->execute($faq, $request->validated());

        return $this->ok(message: __('messages.faq_updated_successfully'), data: FaqResource::make($faq));
    }

    public function destroy(Faq $faq, DeleteFaqAction $action): JsonResponse
    {
        $action->execute($faq);

        return $this->ok(message: __('messages.faq_deleted_successfully'));
    }
}
