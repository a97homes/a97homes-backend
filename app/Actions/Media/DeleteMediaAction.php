<?php

namespace App\Actions\Media;

use Illuminate\Database\Eloquent\Model;

class DeleteMediaAction
{
    public function execute(Model $model, int $mediaId): bool
    {
        $media = $model->media()->find($mediaId);

        if ($media) {
            $model->deleteMedia($mediaId);

            return true;
        }

        return false;
    }
}
