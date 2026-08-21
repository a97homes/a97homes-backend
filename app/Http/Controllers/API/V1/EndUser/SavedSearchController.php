<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\EndUser;

use App\Enums\SavedSearchTypeEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\EndUser\SavedSearch\StoreSavedSearchRequest;
use App\Http\Requests\API\V1\EndUser\SavedSearch\UpdateSavedSearchRequest;
use App\Http\Resources\API\V1\SavedSearch\SavedSearchCollection;
use App\Http\Resources\API\V1\SavedSearch\SavedSearchResource;
use App\Models\SavedSearch;
use App\Models\User\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SavedSearchController extends Controller
{
    /**
     * List the authenticated user's saved searches (newest first).
     */
    public function index(Request $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $searches = SavedSearch::query()
            ->where('user_id', $user->id)
            ->latest()
            ->macroPaginate();

        return $this->ok(data: new SavedSearchCollection($searches));
    }

    /**
     * Save a new search for the authenticated user.
     */
    public function store(StoreSavedSearchRequest $request): JsonResponse
    {
        /** @var User $user */
        $user = $request->user();

        $search = SavedSearch::create([
            ...$request->validated(),
            'user_id' => $user->id,
        ]);

        return $this->ok(
            message: __('messages.saved_search_created'),
            data: SavedSearchResource::make($search),
        );
    }

    public function show(Request $request, SavedSearch $savedSearch): JsonResponse
    {
        if ($this->notOwner($request, $savedSearch)) {
            return $this->notFound(__('messages.saved_search_not_found'));
        }

        return $this->ok(data: SavedSearchResource::make($savedSearch));
    }

    public function update(UpdateSavedSearchRequest $request, SavedSearch $savedSearch): JsonResponse
    {
        if ($this->notOwner($request, $savedSearch)) {
            return $this->notFound(__('messages.saved_search_not_found'));
        }

        $savedSearch->update($request->validated());

        return $this->ok(
            message: __('messages.saved_search_updated'),
            data: SavedSearchResource::make($savedSearch->refresh()),
        );
    }

    public function destroy(Request $request, SavedSearch $savedSearch): JsonResponse
    {
        if ($this->notOwner($request, $savedSearch)) {
            return $this->notFound(__('messages.saved_search_not_found'));
        }

        $savedSearch->delete();

        return $this->ok(message: __('messages.saved_search_deleted'));
    }

    /**
     * Execute a saved search by internally dispatching to the relevant
     * public list endpoint with the stored criteria. Stamps
     * last_checked_at so clients can tell when the user last viewed
     * it (useful when notify_by_email is enabled).
     */
    public function run(Request $request, SavedSearch $savedSearch): JsonResponse
    {
        if ($this->notOwner($request, $savedSearch)) {
            return $this->notFound(__('messages.saved_search_not_found'));
        }

        $savedSearch->update(['last_checked_at' => now()]);

        $criteria = (array) ($savedSearch->criteria ?? []);
        $target = match ($savedSearch->type) {
            SavedSearchTypeEnum::Property => '/api/V1/properties',
            default => '/api/V1/compounds',
        };

        $internal = Request::create($target, 'GET', $criteria);
        $internal->headers->replace($request->headers->all());

        /** @var \Illuminate\Http\Response $response */
        $response = app()->handle($internal);

        return response()->json(
            json_decode($response->getContent(), true),
            $response->getStatusCode(),
        );
    }

    private function notOwner(Request $request, SavedSearch $savedSearch): bool
    {
        /** @var User|null $user */
        $user = $request->user();

        return $user === null || $savedSearch->user_id !== $user->id;
    }
}
