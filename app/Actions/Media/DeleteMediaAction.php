<?php

namespace App\Actions\Media;

use App\Models\Property;

class DeleteMediaAction
{
    public function execute(Property $property, int $mediaId): bool
    {
		// TODO: check if the media is in the property collection
        $property->deleteMedia($mediaId);

        return true;

    }
}
