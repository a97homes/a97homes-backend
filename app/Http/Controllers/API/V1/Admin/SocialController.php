<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Social\DeleteSocialAction;
use App\Actions\Social\StoreSocialAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Social\StoreSocialRequest;
use App\Http\Resources\API\V1\Social\SocialResource;
use App\Models\Social;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class SocialController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_SOCIALS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_SOCIALS_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_SOCIALS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_SOCIALS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $socials = QueryBuilder::for(Social::class)
            ->allowedSorts([
                AllowedSort::field('id'),
            ])
            ->defaultSort('-id')
            ->macroPaginate();

        return $this->ok(
            data: SocialResource::collection($socials)
        );
    }

    public function store(StoreSocialRequest $request, StoreSocialAction $action): JsonResponse
    {
        $social = $action->execute($request->validated());

        return $this->ok(message: __('messages.social_contact_created_successfully'), data: SocialResource::make($social));
    }

    public function show(Social $social): JsonResponse
    {
        return $this->ok(data: SocialResource::make($social->load('media')));
    }

    public function destroy(Social $social, DeleteSocialAction $action): JsonResponse
    {
        $action->execute($social);

        return $this->ok(message: __('messages.social_contact_deleted_successfully'));
    }
}
