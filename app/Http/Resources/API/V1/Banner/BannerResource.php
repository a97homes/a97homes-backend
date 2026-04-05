<?php

namespace App\Http\Resources\API\V1\Banner;

use App\Models\Banner;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BannerResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Banner $banner */
        $banner = $this->resource;

        return [
            'id' => $banner->id,
            'title' => $this->getTranslatableField($banner, 'title'),
            'subtitle' => $this->getTranslatableField($banner, 'subtitle'),
            'link' => $banner->link,
            'image_url' => $banner->getFirstMediaUrl(Banner::MEDIA_COLLECTION_IMAGE) ?: null,
        ];
    }
}
