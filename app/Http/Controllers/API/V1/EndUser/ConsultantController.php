<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Filters\ConsultantSearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Consultant\VerifyConsultantPhoneRequest;
use App\Http\Resources\API\V1\Consultant\ConsultantCollection;
use App\Http\Resources\API\V1\Consultant\ConsultantResource;
use App\Models\Consultant;
use App\Models\ConsultantPhone;
use Illuminate\Http\JsonResponse;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ConsultantController extends Controller
{
    public function index(): JsonResponse
    {
        $consultants = QueryBuilder::for(Consultant::class)
            ->with(['phones'])
            ->allowedFilters([
                AllowedFilter::custom('search', new ConsultantSearchFilter),
                AllowedFilter::exact('is_featured'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('sales_percentage'),
                AllowedSort::field('created_at'),
            ])
            ->macroPaginate();

        return $this->ok(data: new ConsultantCollection($consultants));
    }

    public function show(Consultant $consultant): JsonResponse
    {
        $consultant->load(['phones']);

        return $this->ok(data: ConsultantResource::make($consultant));
    }

    public function verify(VerifyConsultantPhoneRequest $request): JsonResponse
    {
        $phone = ConsultantPhone::where('phone', $request->validated('phone'))->first();

        if (! $phone) {
            return $this->notFound(message: __('messages.consultant_not_found'));
        }

        $consultant = $phone->consultant()->with('phones')->first();

        return $this->ok(
            message: __('messages.consultant_verified_successfully'),
            data: ConsultantResource::make($consultant)
        );
    }
}
