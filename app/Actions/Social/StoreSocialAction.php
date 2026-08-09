<?php

namespace App\Actions\Social;

use App\Models\Social;

class StoreSocialAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Social
    {
        $icon = $data['icon'] ?? null;
        unset($data['icon']);

        $social = Social::create($data);

        if ($icon) {
            $social->addMedia($icon)->toMediaCollection(Social::MEDIA_COLLECTION_ICON);
        }

        return $social;
    }
}
