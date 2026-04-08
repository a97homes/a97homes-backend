<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Actions\Consultant\StoreConsultantReviewAction;
use App\Filters\ConsultantSearchFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\Consultant\StoreConsultantReviewRequest;
use App\Http\Requests\API\V1\EndUser\Consultant\VerifyConsultantPhoneRequest;
use App\Http\Resources\API\V1\Consultant\ConsultantCollection;
use App\Http\Resources\API\V1\Consultant\ConsultantResource;
use App\Http\Resources\API\V1\Consultant\ConsultantReviewCollection;
use App\Http\Resources\API\V1\Consultant\ConsultantReviewResource;
use App\Http\Resources\API\V1\Property\PropertyCollection;
use App\Models\Consultant;
use App\Models\ConsultantPhone;
use App\Models\ConsultantReview;
use App\Models\Property;
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
        $consultant->load(['phones', 'reviews']);

        return $this->ok(data: ConsultantResource::make($consultant));
    }

    public function properties(Consultant $consultant): JsonResponse
    {
        $properties = QueryBuilder::for(Property::where('consultant_id', $consultant->id))
            ->with(['city', 'propertyType', 'compound', 'attributes', 'selectedOptions', 'media'])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('price'),
                AllowedSort::field('created_at'),
            ])
            ->macroPaginate();

        return $this->ok(data: new PropertyCollection($properties));
    }

    public function reviews(Consultant $consultant): JsonResponse
    {
        $reviews = QueryBuilder::for(ConsultantReview::where('consultant_id', $consultant->id))
            ->with(['user.media'])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('overall_rating'),
                AllowedSort::field('created_at'),
            ])
            ->macroPaginate();

        return $this->ok(data: new ConsultantReviewCollection($reviews));
    }

    public function storeReview(StoreConsultantReviewRequest $request, Consultant $consultant, StoreConsultantReviewAction $action): JsonResponse
    {
        $data = $request->validated();
        $data['user_id'] = $request->user()->id;

        $review = $action->handle($consultant, $data);
        $review->load('user.media');

        return $this->ok(
            message: __('messages.review_created_successfully'),
            data: ConsultantReviewResource::make($review)
        );
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
