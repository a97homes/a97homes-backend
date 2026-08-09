<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Page\PageResource;
use App\Models\Page;
use Illuminate\Http\JsonResponse;

class PageController extends Controller
{
    /**
     * List published static pages (About, Terms, Privacy, …).
     * Ordered by sort_order then newest.
     */
    public function index(): JsonResponse
    {
        $pages = Page::query()
            ->published()
            ->orderBy('sort_order')
            ->latest()
            ->get();

        return $this->ok(data: PageResource::collection($pages));
    }

    /**
     * Show a published page by slug. 404 for drafts.
     */
    public function show(string $slug): JsonResponse
    {
        $page = Page::query()
            ->published()
            ->where('slug', $slug)
            ->firstOrFail();

        return $this->ok(data: PageResource::make($page));
    }
}
