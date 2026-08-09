<?php

namespace App\Actions\Developer;

use App\Models\Developer;

class StoreDeveloperAction
{
    public function execute(array $data): Developer
    {
        $logo = $data['logo'] ?? null;
        $banner = $data['banner'] ?? null;
        unset($data['logo'], $data['banner']);

        $developer = Developer::create($data);

        if ($logo) {
            $developer->addMedia($logo)->toMediaCollection(Developer::MEDIA_COLLECTION_LOGO);
        }

        if ($banner) {
            $developer->addMedia($banner)->toMediaCollection(Developer::MEDIA_COLLECTION_BANNER);
        }

        return $developer;
    }
}
