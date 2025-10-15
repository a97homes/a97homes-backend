<?php

namespace App\Actions\Media;

use App\Models\Property;

class DeleteMediaAction
{
    public function execute(Property $property, int $mediaId): bool
    {
        $media = $property->media()->find($mediaId);

        if ($media) {
            $property->deleteMedia($mediaId);
        }

        return false;
    }
}
