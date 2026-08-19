<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\City\CityResource;
use App\Models\State;
use Illuminate\Http\JsonResponse;

class StateController extends Controller
{
    public function cities(State $state): JsonResponse
    {
        $cities = $state->cities()->select('id', 'name', 'state_id')->get();

        return $this->ok(data: CityResource::collection($cities));
    }
}
