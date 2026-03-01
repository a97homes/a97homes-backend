<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Actions\SellUnit\StoreSellUnitAction;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\SellUnit\StoreSellUnitRequest;
use App\Http\Resources\API\V1\SellUnit\SellUnitResource;
use Illuminate\Http\JsonResponse;

class SellUnitController extends Controller
{
    public function store(StoreSellUnitRequest $request, StoreSellUnitAction $action): JsonResponse
    {
        $sellUnit = $action->execute($request->validated());

        return $this->ok(
            message: __('messages.sell_unit_created_successfully'),
            data: new SellUnitResource($sellUnit->load('propertyType:name,id', 'city:name,id'))
        );
    }
}
