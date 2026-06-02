<?php

declare(strict_types=1);

namespace App\Actions\Banner;

use App\Models\Banner;

class UpdateBannerAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(Banner $banner, array $data): Banner
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        $banner->update($data);

        if ($image) {
            $banner->clearMediaCollection(Banner::MEDIA_COLLECTION_IMAGE);
            $banner->addMedia($image)->toMediaCollection(Banner::MEDIA_COLLECTION_IMAGE);
        }

        return $banner->refresh();
    }
}
