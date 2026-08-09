<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Phase\PhaseResource;
use App\Models\Compound;
use Illuminate\Http\JsonResponse;

class PhaseController extends Controller
{
    /**
     * Public list of a compound's phases, ordered by sort_order.
     */
    public function byCompound(Compound $compound): JsonResponse
    {
        $phases = $compound->phases()->get();

        return $this->ok(data: PhaseResource::collection($phases));
    }
}
