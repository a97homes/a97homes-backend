<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\State\DeleteStateAction;
use App\Actions\State\StoreStateAction;
use App\Actions\State\UpdateStateAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\State\StoreStateRequest;
use App\Http\Requests\API\V1\Admin\State\UpdateStateRequest;
use App\Http\Resources\State\StateCollection;
use App\Http\Resources\State\StateResource;
use App\Models\State;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class StateController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_STATES_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_STATES_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_STATES_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_STATES_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_STATES_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $states = QueryBuilder::for(State::class)
            ->with(['country:id,name'])
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::exact('country_id'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
            ])
            ->macroPaginate();

        return $this->ok(data: new StateCollection($states));
    }

    public function store(StoreStateRequest $request, StoreStateAction $action): JsonResponse
    {
        $state = $action->execute($request->validated());

        return $this->ok(message: __('messages.state_created_successfully'), data: StateResource::make($state));
    }

    public function update(UpdateStateRequest $request, State $state, UpdateStateAction $action): JsonResponse
    {
        $action->execute($state, $request->validated());

        return $this->ok(message: __('messages.state_updated_successfully'), data: StateResource::make($state));
    }

    public function show(State $state): JsonResponse
    {
        return $this->ok(data: StateResource::make($state->load(['country:id,name'])));
    }

    public function destroy(State $state, DeleteStateAction $action): JsonResponse
    {
        $action->execute($state);

        return $this->ok(message: __('messages.state_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $states = State::select('id', 'name')->get();

        return $this->ok(data: StateResource::collection($states));
    }
}
