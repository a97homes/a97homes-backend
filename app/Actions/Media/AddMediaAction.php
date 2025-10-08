<?php

namespace App\Actions\Media;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class AddMediaAction
{
    public function execute(Model $model, array $data, string $collection): ?Media
    {
        $data = collect($data);
        if ($data->has('file')) {
            return $model->addMedia($data->get('file'))->toMediaCollection($collection);
        }

        return null;
    }
}
