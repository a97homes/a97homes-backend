<?php

namespace App\Actions\Media;

use App\Models\Property;

class DeleteMediaAction
{
    public function execute(Property $property)
    {
        // TODO:
        return $property->clearMediaCollection(Property::MEDIA_COLLECTION_FILE);
    }
}
