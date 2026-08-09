<?php

namespace App\Actions\Developer;

use App\Models\Developer;

class UpdateDeveloperAction
{
    public function execute(Developer $developer, array $data): Developer
    {
        $logo = $data['logo'] ?? null;
        $banner = $data['banner'] ?? null;
        unset($data['logo'], $data['banner']);

        $developer->update($data);

        if ($logo) {
            $developer->clearMediaCollection(Developer::MEDIA_COLLECTION_LOGO);
            $developer->addMedia($logo)->toMediaCollection(Developer::MEDIA_COLLECTION_LOGO);
        }

        if ($banner) {
            $developer->clearMediaCollection(Developer::MEDIA_COLLECTION_BANNER);
            $developer->addMedia($banner)->toMediaCollection(Developer::MEDIA_COLLECTION_BANNER);
        }

        return $developer;
    }
}
