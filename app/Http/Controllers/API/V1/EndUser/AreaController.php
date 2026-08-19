<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\SubArea\SubAreaResource;
use App\Models\Area;
use Illuminate\Http\JsonResponse;

class AreaController extends Controller
{
    public function subAreas(Area $area): JsonResponse
    {
        $subAreas = $area->subAreas()->select('id', 'name', 'area_id')->get();

        return $this->ok(data: SubAreaResource::collection($subAreas));
    }
}
