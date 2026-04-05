<?php

namespace App\Actions\Developer;

use App\Models\Developer;

class UpdateDeveloperAction
{
    public function execute(Developer $developer, array $data): Developer
    {
        $logo = $data['logo'] ?? null;
        unset($data['logo']);

        $developer->update($data);

        if ($logo) {
            $developer->clearMediaCollection(Developer::MEDIA_COLLECTION_LOGO);
            $developer->addMedia($logo)->toMediaCollection(Developer::MEDIA_COLLECTION_LOGO);
        }

        return $developer;
    }
}
