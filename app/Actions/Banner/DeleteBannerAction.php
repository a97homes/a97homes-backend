<?php

declare(strict_types=1);

namespace App\Actions\Banner;

use App\Models\Banner;

class DeleteBannerAction
{
    public function execute(Banner $banner): void
    {
        $banner->clearMediaCollection(Banner::MEDIA_COLLECTION_IMAGE);
        $banner->delete();
    }
}
