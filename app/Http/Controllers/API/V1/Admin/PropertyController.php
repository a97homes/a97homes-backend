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
use App\Http\Requests\API\V1\Admin\Property\StorePropertyRequest;
use App\Http\Requests\API\V1\Admin\Property\UpdatePropertyRequest;
use App\Http\Requests\API\V1\Admin\Property\UpdatePropertyStatusRequest;
use App\Http\Resources\API\V1\Media\MediaResource;
use App\Http\Resources\API\V1\Property\PropertyCollection;
use App\Http\Resources\API\V1\Property\PropertyResource;
use App\Models\Property;
use App\Permissions\PermissionRegistry;
use Illuminate\Database\Eloquent\Builder;
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
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROPERTIES_INDEX]), only: ['index', 'dropdown']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROPERTIES_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROPERTIES_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROPERTIES_UPDATE]), only: ['update', 'toggleFeature', 'updateStatus', 'addMedia', 'deleteMediaAction']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROPERTIES_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $properties = QueryBuilder::for(Property::class)
            ->with(['subArea:id,name,area_id',
                'subArea.area:id,name,country_id',
                'subArea.area.country:id,name',
                'compound:id,name,developer_id',
                'compound.developer:id,name',
                'compound.developer.media',
                'attributes:name,id',
                'phones',
                'whatsappNumbers'])

            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('attributes.name'),
                AllowedFilter::partial('address'),
                AllowedFilter::exact('compound_id'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
                AllowedFilter::exact('compound.developer_id'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
                AllowedSort::callback('created_at', function (Builder $query, bool $descending): void {
                    $direction = $descending ? 'desc' : 'asc';
                    $query->orderBy('created_at', $direction)->orderBy('id', $direction);
                }),
            ])
            ->macroPaginate();

        return $this->ok(data: new PropertyCollection($properties));
    }

    public function store(StorePropertyRequest $request, StorePropertyAction $action): JsonResponse
    {
        $property = $action->execute($request->validated());

        return $this->ok(message: __('messages.property_created_successfully'), data: PropertyResource::make($property));
    }

    public function update(UpdatePropertyRequest $request, Property $property, UpdatePropertyAction $action): JsonResponse
    {
        $action->execute($property, $request->validated());
        $property->load(['attributes:name,id', 'phones', 'whatsappNumbers']);

        return $this->ok(message: __('messages.property_updated_successfully'), data: PropertyResource::make($property));
    }

    public function show(Property $property): JsonResponse
    {
        $property->load(['subArea:id,name,area_id', 'subArea.area:id,name,country_id', 'subArea.area.country:id,name', 'attributes:name,id', 'compound:id,name,developer_id', 'compound.developer:id,name', 'compound.developer.media', 'compound.phases:id,name,compound_id', 'phones', 'whatsappNumbers']);

        return $this->ok(data: PropertyResource::make($property));
    }

    public function destroy(Property $property, DeletePropertyAction $action): JsonResponse
    {
        $action->execute($property);

        return $this->ok(message: __('messages.property_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $properties = Property::select('id', 'name')->with(['phones', 'whatsappNumbers'])->get();

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

    public function updateStatus(UpdatePropertyStatusRequest $request, Property $property, UpdatePropertyStatusAction $action): JsonResponse
    {
        $property = $action->execute($property, $request->validated('status'));

        return $this->ok(message: __('messages.property_updated_successfully'), data: PropertyResource::make($property));
    }

    public function toggleFeature(Property $property): JsonResponse
    {
        $property->update(['is_featured' => ! $property->is_featured]);

        return $this->ok(
            message: __($property->is_featured ? 'messages.property_featured' : 'messages.property_unfeatured'),
            data: PropertyResource::make($property->refresh()),
        );
    }
}
