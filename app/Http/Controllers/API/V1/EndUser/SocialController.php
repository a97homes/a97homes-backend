<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Social\SocialResource;
use App\Models\Social;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class SocialController extends Controller
{
    public function index(): JsonResponse
    {
        $socials = QueryBuilder::for(Social::class)
            ->with('media')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('type'),
            ])
            ->defaultSort('-id')
            ->macroPaginate();

        return $this->ok(
            data: SocialResource::collection($socials)
        );
    }

    public function show(Social $social): JsonResponse
    {
        return $this->ok(data : SocialResource::make($social->load('media')));
    }
}
