<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Favorite\StoreFavoriteRequest;
use App\Http\Resources\API\V1\Compound\CompoundCollection;
use App\Http\Resources\API\V1\Compound\CompoundResource;
use App\Models\Compound;
use App\Models\Favorite;
use Illuminate\Http\JsonResponse;

class FavoriteController extends Controller
{
    public function index(): JsonResponse
    {
        $userId = authUserId();

        $favoriteCompoundIds = Favorite::where('user_id', $userId)->pluck('compound_id');

        $compounds = Compound::query()
            ->with([
                'developer.media',
                'city:id,name,state_id',
                'city.state:id,name',
                'properties:id,compound_id,property_type_id,price,resale_price',
                'properties.propertyType:id,name',
                'activeDiscount',
            ])
            ->withMin('properties', 'price')
            ->withMin('properties', 'resale_price')
            ->whereIn('id', $favoriteCompoundIds)
            ->paginate();

        $compounds->getCollection()->transform(function (Compound $compound) {
            $compound->is_favorited = true;

            return $compound;
        });

        return $this->ok(data: new CompoundCollection($compounds));
    }

    public function store(StoreFavoriteRequest $request): JsonResponse
    {
        $userId = authUserId();
        $compoundId = $request->input('compound_id');

        Favorite::firstOrCreate([
            'user_id' => $userId,
            'compound_id' => $compoundId,
        ]);

        $compound = Compound::with([
            'developer.media',
            'city:id,name,state_id',
            'city.state:id,name',
            'properties:id,compound_id,property_type_id,price,resale_price',
            'properties.propertyType:id,name',
            'activeDiscount',
        ])
            ->withMin('properties', 'price')
            ->withMin('properties', 'resale_price')
            ->find($compoundId);

        $compound->is_favorited = true;

        return $this->ok(
            message: __('messages.favorite_added_successfully'),
            data: CompoundResource::make($compound),
        );
    }

    public function destroy(Compound $compound): JsonResponse
    {
        $userId = authUserId();

        $deleted = Favorite::where('user_id', $userId)
            ->where('compound_id', $compound->id)
            ->delete();

        if (! $deleted) {
            return $this->notFound(message: __('messages.favorite_not_found'));
        }

        return $this->ok(message: __('messages.favorite_removed_successfully'));
    }
}
