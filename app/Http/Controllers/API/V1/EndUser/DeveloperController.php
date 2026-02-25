<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Developer\DeveloperResource;
use App\Models\Developer;
use Illuminate\Http\JsonResponse;

class DeveloperController extends Controller
{
    public function dropdown(): JsonResponse
    {
        $developers = Developer::select('id', 'name')->get();

        return $this->ok(data: DeveloperResource::collection($developers));
    }
}
