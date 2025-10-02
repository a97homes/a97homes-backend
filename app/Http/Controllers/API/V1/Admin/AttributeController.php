<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Attribute\DeleteAttributeAction;
use App\Actions\Attribute\StoreAttributeAction;
use App\Actions\Attribute\UpdateAttributeAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Attribute\StoreAttributeRequest;
use App\Http\Requests\API\V1\Admin\Attribute\UpdateAttributeRequest;
use App\Http\Resources\API\V1\Attribute\AttributeCollection;
use App\Http\Resources\API\V1\Attribute\AttributeResource;
use App\Models\Attribute;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class AttributeController extends Controller
{
    public function index(): JsonResponse
    {
        $attributes = QueryBuilder::for(Attribute::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::exact('type'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
                AllowedSort::field('type'),
            ])
            ->macroPaginate();

        return $this->ok(data: new AttributeCollection($attributes));
    }

    public function store(StoreAttributeRequest $request, StoreAttributeAction $action): JsonResponse
    {
        $attribute = $action->execute($request->validated());

        return $this->ok(
            message: __('messages.attribute_created_successfully'),
            data: AttributeResource::make($attribute)
        );
    }

    public function update(UpdateAttributeRequest $request, Attribute $attribute, UpdateAttributeAction $action): JsonResponse
    {
        $action->execute($attribute, $request->validated());

        return $this->ok(message: __('messages.attribute_updated_successfully'), data: AttributeResource::make($attribute));
    }

    public function show(Attribute $attribute): JsonResponse
    {
        return $this->ok(data: AttributeResource::make($attribute));
    }

    public function destroy(Attribute $attribute, DeleteAttributeAction $action): JsonResponse
    {
        $action->execute($attribute);

        return $this->ok(message: __('messages.attribute_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $attributes = Attribute::select('id', 'name')->get();

        return $this->ok(data: AttributeResource::collection($attributes));
    }
}
