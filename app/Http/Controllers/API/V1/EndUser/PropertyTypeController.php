<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Attribute\FilterableAttributeResource;
use App\Http\Resources\API\V1\PropertyType\PropertyTypeResource;
use App\Models\PropertyType;
use Illuminate\Http\JsonResponse;

class PropertyTypeController extends Controller
{
    public function dropdown(): JsonResponse
    {
        $propertyTypes = PropertyType::select('id', 'name')->get();

        return $this->ok(data: PropertyTypeResource::collection($propertyTypes));
    }

    public function attributes(PropertyType $propertyType): JsonResponse
    {
        $attributes = $propertyType->attributes()
            ->where('is_filterable', true)
            ->with(['unit'])
            ->orderByPivot('is_required', 'desc')
            ->get();

        return $this->ok(data: FilterableAttributeResource::collection($attributes));
    }
}
