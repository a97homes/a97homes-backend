<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Consultant\DeleteConsultantAction;
use App\Actions\Consultant\StoreConsultantAction;
use App\Actions\Consultant\UpdateConsultantAction;
use App\Enums\Role\UserRoleEnum;
use App\Filters\ConsultantSearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Consultant\StoreConsultantRequest;
use App\Http\Requests\API\V1\Admin\Consultant\UpdateConsultantRequest;
use App\Http\Resources\API\V1\Consultant\ConsultantCollection;
use App\Http\Resources\API\V1\Consultant\ConsultantResource;
use App\Models\Consultant;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ConsultantController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CONSULTANTS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CONSULTANTS_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CONSULTANTS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CONSULTANTS_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CONSULTANTS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $consultants = QueryBuilder::for(Consultant::class)
            ->with(['phones'])
            ->allowedFilters([
                AllowedFilter::custom('search', new ConsultantSearchFilter),
                AllowedFilter::exact('is_featured'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('sales_percentage'),
                AllowedSort::field('created_at'),
            ])
            ->macroPaginate();

        return $this->ok(data: new ConsultantCollection($consultants));
    }

    public function store(StoreConsultantRequest $request, StoreConsultantAction $action): JsonResponse
    {
        $consultant = $action->execute($request->validated());

        return $this->ok(message: __('messages.consultant_created_successfully'), data: ConsultantResource::make($consultant));
    }

    public function show(Consultant $consultant): JsonResponse
    {
        $consultant->load(['phones']);

        return $this->ok(data: ConsultantResource::make($consultant));
    }

    public function update(UpdateConsultantRequest $request, Consultant $consultant, UpdateConsultantAction $action): JsonResponse
    {
        $action->execute($consultant, $request->validated());

        return $this->ok(message: __('messages.consultant_updated_successfully'), data: ConsultantResource::make($consultant));
    }

    public function destroy(Consultant $consultant, DeleteConsultantAction $action): JsonResponse
    {
        $action->execute($consultant);

        return $this->ok(message: __('messages.consultant_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $consultants = Consultant::select('id', 'name')->get();

        return $this->ok(data: ConsultantResource::collection($consultants));
    }
}
