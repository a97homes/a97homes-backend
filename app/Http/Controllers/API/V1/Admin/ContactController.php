<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Contact\DeleteContactAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Contact\ContactCollection;
use App\Http\Resources\API\V1\Contact\ContactResource;
use App\Models\Contact;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ContactController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CONTACTS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CONTACTS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CONTACTS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $contacts = QueryBuilder::for(Contact::class)
            ->allowedFilters([
                AllowedFilter::partial('name'),
                AllowedFilter::partial('email'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
                AllowedSort::field('created_at'),
            ])
            ->macroPaginate();

        return $this->ok(data: new ContactCollection($contacts));
    }

    public function show(Contact $contact): JsonResponse
    {
        return $this->ok(data: ContactResource::make($contact));
    }

    public function destroy(Contact $contact, DeleteContactAction $action): JsonResponse
    {
        $action->execute($contact);

        return $this->ok(message: __('messages.contact_deleted_successfully'));
    }
}
