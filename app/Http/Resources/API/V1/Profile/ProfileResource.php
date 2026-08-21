<?php

namespace App\Http\Resources\API\V1\Profile;

use App\Http\Resources\API\V1\Media\MediaResource;
use App\Models\User\User;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProfileResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var User $user */
        $user = $this->resource;

        return [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'country_code' => $user->country_code,
            'phone' => $user->phone,
            'avatar' => $this->getAvatar($user),
            'email_verified_at' => $user->email_verified_at,
            'created_at' => $user->created_at,
        ];
    }

    private function getAvatar(User $user): ?MediaResource
    {
        $avatar = $user->getFirstMedia(User::MEDIA_COLLECTION_AVATAR);

        return $avatar ? new MediaResource($avatar) : null;
    }
}
