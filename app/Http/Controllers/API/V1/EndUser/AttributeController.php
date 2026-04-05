<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Attribute\AttributeOptionResource;
use App\Models\Attribute;
use Illuminate\Http\JsonResponse;

class AttributeController extends Controller
{
    public function options(Attribute $attribute): JsonResponse
    {
        $options = $attribute->activeOptions()->get();

        return $this->ok(data: AttributeOptionResource::collection($options));
    }
}
