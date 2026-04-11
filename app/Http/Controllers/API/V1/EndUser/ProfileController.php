<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Actions\Media\AddMediaAction;
use App\Actions\Media\DeleteMediaAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Profile\UpdateAvatarRequest;
use App\Http\Requests\API\V1\EndUser\Profile\UpdatePasswordRequest;
use App\Http\Requests\API\V1\EndUser\Profile\UpdateProfileRequest;
use App\Http\Resources\API\V1\Profile\ProfileResource;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;

class ProfileController extends Controller
{
    public function show(): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        return $this->ok(data: new ProfileResource($user));
    }

    public function update(UpdateProfileRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->update($request->validated());

        return $this->ok(
            message: __('messages.profile_updated_successfully'),
            data: new ProfileResource($user->fresh()),
        );
    }

    public function updatePassword(UpdatePasswordRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->update(['password' => $request->validated('new_password')]);

        return $this->ok(message: __('messages.password_updated_successfully'));
    }

    public function updateAvatar(UpdateAvatarRequest $request, AddMediaAction $addMediaAction): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->clearMediaCollection(User::MEDIA_COLLECTION_AVATAR);

        $addMediaAction->execute($user, $request->validated(), User::MEDIA_COLLECTION_AVATAR);

        return $this->ok(
            message: __('messages.avatar_updated_successfully'),
            data: new ProfileResource($user->fresh()),
        );
    }

    public function deleteAvatar(DeleteMediaAction $deleteMediaAction): JsonResponse
    {
        /** @var User $user */
        $user = auth()->user();

        $user->clearMediaCollection(User::MEDIA_COLLECTION_AVATAR);

        return $this->ok(message: __('messages.avatar_deleted_successfully'));
    }
}
