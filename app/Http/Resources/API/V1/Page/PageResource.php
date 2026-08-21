<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\Page;

use App\Models\Page;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PageResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Page $page */
        $page = $this->resource;

        return [
            'id' => $page->id,
            'slug' => $page->slug,
            'title' => $this->getTranslatableField($page, 'title'),
            'body' => $this->when(
                ! $request->boolean('summary', false),
                fn () => $this->getTranslatableField($page, 'body')
            ),
            'is_published' => $page->is_published,
            'published_at' => $page->published_at,
            'sort_order' => $page->sort_order,
            'created_at' => $page->created_at,
            'updated_at' => $page->updated_at,
        ];
    }
}
