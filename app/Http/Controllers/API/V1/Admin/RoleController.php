<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Role\DeleteRoleAction;
use App\Actions\Role\StoreRoleAction;
use App\Actions\Role\UpdateRoleAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\Role\DestroyRoleRequest;
use App\Http\Requests\Role\StoreRoleRequest;
use App\Http\Requests\Role\UpdateRoleRequest;
use App\Http\Resources\API\V1\Role\RoleResource;
use Illuminate\Http\JsonResponse;
use Spatie\Permission\Models\Role;

class RoleController extends Controller
{
    // TODO:: handle this class
    public function index() {}

    public function store(StoreRoleRequest $request, StoreRoleAction $action): JsonResponse
    {
        $action->execute($request->validated());

        return $this->ok(message: __('messages.role_created_successfully'));
    }

    public function show(Role $role): JsonResponse
    {
        return $this->ok(data: RoleResource::make($role));
    }

    public function update(UpdateRoleRequest $request, Role $role, UpdateRoleAction $action): JsonResponse
    {
        $action->execute($role, $request->validated());

        return $this->ok(message: __('messages.role_updated_successfully'));
    }

    public function destroy(DestroyRoleRequest $request, Role $role, DeleteRoleAction $action): JsonResponse
    {
        $action->execute($role->loadCount('users'));

        return $this->ok(message: __('messages.role_deleted_successfully'));
    }
}
