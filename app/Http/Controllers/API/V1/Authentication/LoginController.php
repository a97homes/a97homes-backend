<?php

namespace App\Http\Controllers\API\V1\Authentication;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Http\Resources\API\V1\Authentication\AuthenticationResource;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;

class LoginController extends Controller
{
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::whereEmail($request->email)->first();
        //  $user->load('roles:id,name', 'permissions:id,name');
        $data = AuthenticationResource::make($user);

        return $this->ok(__('auth.signed'), $data);
    }
}
