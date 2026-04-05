<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Country\CountryResource;
use App\Models\Country;
use Illuminate\Http\JsonResponse;

class CountryController extends Controller
{
    public function index(): JsonResponse
    {
        $countries = Country::select('id', 'name', 'code', 'phone_code')->get();

        return $this->ok(data: CountryResource::collection($countries));
    }

    public function states(Country $country): JsonResponse
    {
        $states = $country->states()->select('id', 'name', 'country_id')->get();

        return $this->ok(data: \App\Http\Resources\State\StateResource::collection($states));
    }
}
