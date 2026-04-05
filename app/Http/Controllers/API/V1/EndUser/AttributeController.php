<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Attribute\AttributeOptionResource;
use App\Http\Resources\API\V1\Attribute\FilterableAttributeResource;
use App\Models\Attribute;
use Illuminate\Http\JsonResponse;

class AttributeController extends Controller
{
    /**
     * List all filterable attributes with their options.
     */
    public function filterable(): JsonResponse
    {
        $attributes = Attribute::query()
            ->where('is_filterable', true)
            ->with(['unit', 'activeOptions'])
            ->get();

        return $this->ok(data: FilterableAttributeResource::collection($attributes));
    }

    public function options(Attribute $attribute): JsonResponse
    {
        $options = $attribute->activeOptions()->get();

        return $this->ok(data: AttributeOptionResource::collection($options));
    }
}
