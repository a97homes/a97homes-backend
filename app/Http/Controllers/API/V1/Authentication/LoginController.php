<?php

namespace App\Http\Controllers\API\V1\Authentication;

use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Authentication\LoginRequest;
use App\Http\Resources\API\V1\Authentication\AuthenticationResource;

class LoginController extends Controller
{
    // TODO: adding translations
    public function login(LoginRequest $request): JsonResponse
    {
        $user = User::whereEmail($request->input('email'))->first();
        $user->load('roles:id,name', 'permissions:id,name');
        $data = AuthenticationResource::make($user);

        return $this->ok(__('auth.signed'), $data);
    }
}
