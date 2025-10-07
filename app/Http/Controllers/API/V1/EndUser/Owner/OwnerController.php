<?php

namespace App\Http\Controllers\API\V1\EndUser\Owner;

use App\Models\User\User;
use App\Enums\Role\UserRoleEnum;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;


use App\Http\Requests\API\V1\EndUser\Owner\RegisterOwnerRequest;
use App\Http\Resources\API\V1\Authentication\AuthenticationResource;

class OwnerController extends Controller
{
 public function registerOwner(RegisterOwnerRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::create($request->validated());
        $user->assignRole(UserRoleEnum::OWNER);

        return $this->ok(__('messages.register_successfully'), data: new AuthenticationResource($user));
    }

}
