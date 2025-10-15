<?php

namespace App\Http\Resources\API\V1\Social;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SocialResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Social $social */
        $social = $this->resource;

        return [

            'id' => $this->whenHas('id', fn () => $social->id),
            'link' => $this->whenHas('link', fn () => $social->link),
            'type' => $this->whenHas('type', fn () => $social->type),
            'icon' => $this->whenHas('icon', fn () => $social->icon),
            'created_at' => $this->whenHas('created_at', fn () => $social->created_at),
        ];
    }
}
