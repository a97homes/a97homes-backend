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
        $user = User::query()
            ->when($request->input('email'), fn ($query, $email) => $query->where('email', $email))
            ->when(! $request->input('email') && $request->input('phone'), fn ($query) => $query->where('phone', $request->input('phone'))->where('country_code', $request->input('country_code')))
            ->first();

        $user->load('roles:id,name', 'permissions:id,name');
        $data = AuthenticationResource::make($user);

        return $this->ok(__('auth.signed'), $data);
    }
}
