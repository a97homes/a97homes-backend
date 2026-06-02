<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Compound\DeleteCompoundAction;
use App\Actions\Compound\StoreCompoundAction;
use App\Actions\Compound\UpdateCompoundAction;
use App\Actions\Media\AddMediaAction;
use App\Actions\Media\DeleteMediaAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Compound\AddCompoundMediaRequest;
use App\Http\Requests\API\V1\Admin\Compound\StoreCompoundRequest;
use App\Http\Requests\API\V1\Admin\Compound\UpdateCompoundRequest;
use App\Http\Resources\API\V1\Compound\AdminCompoundCollection;
use App\Http\Resources\API\V1\Compound\AdminCompoundResource;
use App\Http\Resources\API\V1\Media\MediaResource;
use App\Models\Compound;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class CompoundController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_COMPOUNDS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_COMPOUNDS_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_COMPOUNDS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_COMPOUNDS_UPDATE]), only: ['update', 'toggleFeature']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_COMPOUNDS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $compounds = QueryBuilder::for(Compound::class)
            ->allowedFilters([
                AllowedFilter::exact('developer_id'),
                AllowedFilter::partial('name'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts(['id'])
            ->macroPaginate();

        return $this->ok(data: new AdminCompoundCollection($compounds));
    }

    public function store(StoreCompoundRequest $request, StoreCompoundAction $action): JsonResponse
    {
        $compound = $action->execute($request->validated());

        return $this->ok(message: __('messages.compound_created_successfully'), data: AdminCompoundResource::make($compound));
    }

    public function show(Compound $compound): JsonResponse
    {
        return $this->ok(data: AdminCompoundResource::make($compound->load('developer:id,name')));
    }

    public function update(UpdateCompoundRequest $request, Compound $compound, UpdateCompoundAction $action): JsonResponse
    {
        $action->execute($compound, $request->validated());

        return $this->ok(message: __('messages.compound_updated_successfully'), data: AdminCompoundResource::make($compound));
    }

    public function destroy(Compound $compound, DeleteCompoundAction $action): JsonResponse
    {
        $action->execute($compound);

        return $this->ok(message: __('messages.compound_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $compounds = Compound::select('id', 'name')->get();

        return $this->ok(data: AdminCompoundResource::collection($compounds));
    }

    public function addMedia(AddCompoundMediaRequest $request, Compound $compound, AddMediaAction $action): JsonResponse
    {
        $media = $action->execute($compound, $request->validated(), Compound::MEDIA_COLLECTION_IMAGE);

        return $this->ok(message: __('messages.media_compound_added_successfully'), data: MediaResource::make($media));
    }

    public function deleteMedia(Compound $compound, Media $media, DeleteMediaAction $action): JsonResponse
    {
        $action->execute($compound, $media->id);

        return $this->ok(message: __('messages.media_compound_deleted_successfully'));
    }

    public function toggleFeature(Compound $compound): JsonResponse
    {
        $compound->update(['is_featured' => ! $compound->is_featured]);

        return $this->ok(
            message: __($compound->is_featured ? 'messages.compound_featured' : 'messages.compound_unfeatured'),
            data: AdminCompoundResource::make($compound->refresh()),
        );
    }
}
