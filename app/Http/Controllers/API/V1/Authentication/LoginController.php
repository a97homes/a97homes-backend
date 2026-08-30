<?php

namespace App\Http\Controllers\API\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Authentication\UserLoginRequest;
use App\Http\Resources\API\V1\Authentication\AuthenticationResource;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function login(UserLoginRequest $request): JsonResponse
    {
        $user = $request->resolveUser();

        $user->load('roles:id,name', 'roles.permissions:id,name', 'permissions:id,name');

        return $this->ok(__('auth.signed'), AuthenticationResource::make($user));
    }
}
