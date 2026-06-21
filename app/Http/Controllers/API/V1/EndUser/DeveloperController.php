<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Filters\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Developer\DeveloperCollection;
use App\Http\Resources\API\V1\Developer\DeveloperResource;
use App\Models\Developer;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class DeveloperController extends Controller
{
    public function index(): JsonResponse
    {
        $developers = QueryBuilder::for(Developer::query()->active())
            ->with(['media'])
            ->withCount('compounds')
            ->allowedFilters([
                AllowedFilter::custom('name', new NameFilter),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('name'),
            ])
            ->macroPaginate();

        return $this->ok(data: new DeveloperCollection($developers));
    }

    public function show(Developer $developer): JsonResponse
    {
        abort_unless($developer->is_active, 404);

        $developer->load([
            'media',
        ]);

        $developer->loadCount('compounds');

        return $this->ok(data: DeveloperResource::make($developer));
    }

    public function dropdown(): JsonResponse
    {
        $developers = Developer::query()->active()->select('id', 'name')->get();

        return $this->ok(data: DeveloperResource::collection($developers));
    }
}
