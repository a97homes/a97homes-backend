<?php

namespace App\Actions\Media;

use App\Models\Property;

class DeleteMediaAction
{
    public function execute(Property $property, int $mediaId)
    {

        $property->deleteMedia($mediaId);

    }
}
