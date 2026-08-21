<?php

declare(strict_types=1);

namespace App\Actions\Banner;

use App\Models\Banner;

class StoreBannerAction
{
    /**
     * @param  array<string, mixed>  $data
     */
    public function execute(array $data): Banner
    {
        $image = $data['image'] ?? null;
        unset($data['image']);

        $banner = Banner::create($data);

        if ($image) {
            $banner->addMedia($image)->toMediaCollection(Banner::MEDIA_COLLECTION_IMAGE);
        }

        return $banner;
    }
}
