<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\State\DeleteStateAction;
use App\Actions\State\StoreStateAction;
use App\Actions\State\UpdateStateAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\State\StoreStateRequest;
use App\Http\Requests\API\V1\Admin\State\UpdateStateRequest;
use App\Http\Resources\State\StateCollection;
use App\Http\Resources\State\StateResource;
use App\Models\State;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class StateController extends Controller
{
    public function index(): JsonResponse
    {
        $states = QueryBuilder::for(State::class)
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

        $data = new StateCollection($states);

        return $this->ok(data: $data);
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
        return $this->ok(data: StateResource::make($state));
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
