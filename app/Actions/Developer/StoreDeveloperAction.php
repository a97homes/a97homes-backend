<?php

namespace App\Actions\Developer;

use App\Models\Developer;

class StoreDeveloperAction
{
    public function execute(array $data): Developer
    {
        $logo = $data['logo'] ?? null;
        unset($data['logo']);

        $developer = Developer::create($data);

        if ($logo) {
            $developer->addMedia($logo)->toMediaCollection(Developer::MEDIA_COLLECTION_LOGO);
        }

        return $developer;
    }
}
