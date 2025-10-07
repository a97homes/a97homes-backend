<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\registerRequest;
use App\Http\Resources\API\V1\Authentication\AuthenticationResource;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    public function register(RegisterRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = User::create($request->validated()); // TODO: isolate it to action

        return $this->ok(__('messages.register_successfully'), data: new AuthenticationResource($user));
    }
}
