<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Media\AddMediaAction;
use App\Actions\Media\DeleteMediaAction;
use App\Actions\Property\DeletePropertyAction;
use App\Actions\Property\StorePropertyAction;
use App\Actions\Property\UpdatePropertyAction;
use App\Actions\Property\UpdatePropertyStatusAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Property\AddPropertyMediaRequest;
use App\Http\Requests\API\V1\Admin\Property\UpdatePropertyRequest;
use App\Http\Requests\API\V1\Admin\Property\UpdatePropertyStatusRequest;
use App\Http\Resources\API\V1\Media\MediaResource;
use App\Http\Resources\API\V1\Property\PropertyCollection;
use App\Http\Resources\API\V1\Property\PropertyResource;
use App\Models\Property;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class PropertyController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROPERTIES_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROPERTIES_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROPERTIES_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROPERTIES_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROPERTIES_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $properties = QueryBuilder::for(Property::class)
            ->with(['city:id,name,state_id',
                'city.state:id,name,country_id',
                'city.state.country:id,name',
                'attributes:name,id'])

            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('attributes.name'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
            ])
            ->macroPaginate();

        return $this->ok(data: new PropertyCollection($properties));
    }

    public function store(StorePropertyAction $action): JsonResponse
    {
        $property = $action->execute();

        return $this->ok(message: __('messages.property_created_successfully'), data: PropertyResource::make($property));
    }

    public function update(UpdatePropertyRequest $request, Property $property, UpdatePropertyAction $action): JsonResponse
    {
        $property->load('attributes:name,id');
        $action->execute($property, $request->validated());

        return $this->ok(message: __('messages.property_updated_successfully'), data: PropertyResource::make($property));
    }

    public function show(Property $property): JsonResponse
    {
        $property->load(['city:id,name,state_id', 'city.state:id,name,country_id', 'city.state.country:id,name', 'attributes:name,id']);

        return $this->ok(data: PropertyResource::make($property));
    }

    public function destroy(Property $property, DeletePropertyAction $action): JsonResponse
    {
        $action->execute($property);

        return $this->ok(message: __('messages.property_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $properties = Property::select('id', 'name')->get();

        return $this->ok(data: PropertyResource::collection($properties));
    }

    public function addMedia(AddPropertyMediaRequest $request, Property $property, AddMediaAction $action): JsonResponse
    {
        $media = $action->execute($property, $request->validated(), Property::MEDIA_COLLECTION_FILE);

        return $this->ok(message: __('messages.media_property_added_successfully'), data: MediaResource::make($media));

    }

    public function deleteMediaAction(Property $property, Media $media, DeleteMediaAction $action): JsonResponse
    {
        $action->execute($property, $media->id);

        return $this->ok(message: __('messages.media_property_deleted_successfully'));
    }

    public function updateStatus(UpdatePropertyStatusRequest $request, Property $property, UpdatePropertyStatusAction $action)
    {
        $property = $action->execute($property, $request->validated());

        return $this->ok(message: __('messages.property_updated_successfully'), data: PropertyResource::make($property));
    }
}
