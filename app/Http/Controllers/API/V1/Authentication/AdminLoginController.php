<?php

namespace App\Http\Controllers\API\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Authentication\AdminLoginRequest;
use App\Http\Resources\API\V1\Authentication\AuthenticationResource;
use Illuminate\Http\JsonResponse;

class AdminLoginController extends Controller
{
    public function login(AdminLoginRequest $request): JsonResponse
    {
        $user = $request->resolveUser();

        $user->load('roles:id,name', 'permissions:id,name');

        return $this->ok(__('auth.signed'), AuthenticationResource::make($user));
    }
}
