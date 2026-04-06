<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\PropertyFavorite\StorePropertyFavoriteRequest;
use App\Http\Resources\API\V1\Property\PropertyCollection;
use App\Http\Resources\API\V1\Property\PropertyResource;
use App\Models\Property;
use App\Models\PropertyFavorite;
use Illuminate\Http\JsonResponse;

class PropertyFavoriteController extends Controller
{
    public function index(): JsonResponse
    {
        $userId = authUserId();

        $favoritePropertyIds = PropertyFavorite::where('user_id', $userId)->pluck('property_id');

        $properties = Property::query()
            ->with([
                'propertyType:id,name',
                'city:id,name,state_id',
                'city.state:id,name',
                'compound:id,name',
                'compound.developer.media',
                'media',
            ])
            ->whereIn('id', $favoritePropertyIds)
            ->macroPaginate();

        $properties->getCollection()->transform(function (Property $property) {
            $property->is_favorited = true;

            return $property;
        });

        return $this->ok(data: new PropertyCollection($properties));
    }

    public function store(StorePropertyFavoriteRequest $request): JsonResponse
    {
        $userId = authUserId();
        $propertyId = $request->input('property_id');

        PropertyFavorite::firstOrCreate([
            'user_id' => $userId,
            'property_id' => $propertyId,
        ]);

        $property = Property::with([
            'propertyType:id,name',
            'city:id,name,state_id',
            'city.state:id,name',
            'compound:id,name',
            'compound.developer.media',
            'media',
        ])->find($propertyId);

        $property->is_favorited = true;

        return $this->ok(
            message: __('messages.favorite_added_successfully'),
            data: PropertyResource::make($property),
        );
    }

    public function destroy(Property $property): JsonResponse
    {
        $userId = authUserId();

        $deleted = PropertyFavorite::where('user_id', $userId)
            ->where('property_id', $property->id)
            ->delete();

        if (! $deleted) {
            return $this->notFound(message: __('messages.favorite_not_found'));
        }

        return $this->ok(message: __('messages.favorite_removed_successfully'));
    }
}
