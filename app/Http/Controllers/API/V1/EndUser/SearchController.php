<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Compound\CompoundCollection;
use App\Http\Resources\API\V1\Developer\DeveloperCollection;
use App\Http\Resources\API\V1\Property\PropertyCollection;
use App\Http\Resources\City\CityCollection;
use App\Models\City;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\Property;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class SearchController extends Controller
{
    public const SUGGEST_DEFAULT_LIMIT = 5;

    public const SUGGEST_MAX_LIMIT = 10;

    public const SEARCHABLE_TYPES = ['compound', 'property', 'developer', 'city'];

    /**
     * Autocomplete — returns a short list for each entity type matching
     * the query. Designed to power a header search dropdown.
     *
     * Query params:
     *   q     (required, min:2)
     *   limit (optional, 1..10, default 5)
     */
    public function suggest(Request $request): JsonResponse
    {
        $query = trim((string) $request->query('q', ''));

        if (mb_strlen($query) < 2) {
            return $this->ok(data: $this->emptySuggestPayload());
        }

        $limit = (int) $request->query('limit', (string) self::SUGGEST_DEFAULT_LIMIT);
        $limit = max(1, min(self::SUGGEST_MAX_LIMIT, $limit));

        return $this->ok(data: [
            'query' => $query,
            'compounds' => $this->searchCompounds($query, $limit)
                ->map(fn (Compound $c) => [
                    'id' => $c->id,
                    'type' => 'compound',
                    'name' => $c->name,
                    'subtitle' => optional($c->city)->name,
                    'image_url' => $c->getFirstMediaUrl(Compound::MEDIA_COLLECTION_IMAGE) ?: null,
                ])->values(),
            'properties' => $this->searchProperties($query, $limit)
                ->map(fn (Property $p) => [
                    'id' => $p->id,
                    'type' => 'property',
                    'name' => $p->getTranslation('name', app()->getLocale()),
                    'subtitle' => optional(optional($p->compound)->city)->getTranslation('name', app()->getLocale()),
                    'image_url' => $p->getFirstMediaUrl(Property::MEDIA_COLLECTION_FILE) ?: null,
                ])->values(),
            'developers' => $this->searchDevelopers($query, $limit)
                ->map(fn (Developer $d) => [
                    'id' => $d->id,
                    'type' => 'developer',
                    'name' => $d->name,
                    'subtitle' => null,
                    'image_url' => $d->getFirstMediaUrl(Developer::MEDIA_COLLECTION_LOGO) ?: null,
                ])->values(),
            'cities' => $this->searchCities($query, $limit)
                ->map(fn (City $c) => [
                    'id' => $c->id,
                    'type' => 'city',
                    'name' => $c->getTranslation('name', app()->getLocale()),
                    'subtitle' => optional($c->state)->getTranslation('name', app()->getLocale()),
                    'image_url' => $c->getFirstMediaUrl(City::MEDIA_COLLECTION_IMAGE) ?: null,
                ])->values(),
        ]);
    }

    /**
     * Paginated per-type search. `type` must be one of compound,
     * property, developer, city (defaults to compound).
     *
     * Query params:
     *   q        (required, min:2)
     *   type     (optional, default compound)
     *   per_page (optional, default 10, honored via macroPaginate)
     */
    public function search(Request $request): JsonResponse
    {
        $queryString = trim((string) $request->query('q', ''));
        $type = strtolower((string) $request->query('type', 'compound'));

        if (! in_array($type, self::SEARCHABLE_TYPES, true)) {
            return $this->unprocessable(__('messages.invalid_search_type'));
        }

        if (mb_strlen($queryString) < 2) {
            return $this->unprocessable(__('messages.search_query_too_short'));
        }

        return match ($type) {
            'compound' => $this->ok(data: new CompoundCollection(
                Compound::query()
                    ->with(['developer:id,name', 'developer.media', 'city:id,name,state_id', 'city.state:id,name', 'media'])
                    ->searchByName($queryString)
                    ->latest()
                    ->macroPaginate()
            )),
            'property' => $this->ok(data: new PropertyCollection(
                Property::query()
                    ->with(['city:id,name,state_id', 'city.state:id,name', 'propertyType:id,name', 'compound:id,name', 'media'])
                    ->where('status', 'active')
                    ->searchByName($queryString)
                    ->latest()
                    ->macroPaginate()
            )),
            'developer' => $this->ok(data: new DeveloperCollection(
                Developer::query()
                    ->with(['media'])
                    ->searchByName($queryString)
                    ->latest()
                    ->macroPaginate()
            )),
            'city' => $this->ok(data: new CityCollection(
                City::query()
                    ->with(['state:id,name', 'media'])
                    ->searchByName($queryString)
                    ->latest()
                    ->macroPaginate()
            )),
        };
    }

    /**
     * @return Collection<int, Compound>
     */
    private function searchCompounds(string $value, int $limit): Collection
    {
        return Compound::query()
            ->with(['city:id,name', 'media'])
            ->searchByName($value)
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, Property>
     */
    private function searchProperties(string $value, int $limit): Collection
    {
        return Property::query()
            ->with(['compound.city:id,name', 'media'])
            ->where('status', 'active')
            ->searchByName($value)
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, Developer>
     */
    private function searchDevelopers(string $value, int $limit): Collection
    {
        return Developer::query()
            ->with(['media'])
            ->searchByName($value)
            ->limit($limit)
            ->get();
    }

    /**
     * @return Collection<int, City>
     */
    private function searchCities(string $value, int $limit): Collection
    {
        return City::query()
            ->with(['state:id,name', 'media'])
            ->searchByName($value)
            ->limit($limit)
            ->get();
    }

    /**
     * @return array<string, mixed>
     */
    private function emptySuggestPayload(): array
    {
        return [
            'query' => '',
            'compounds' => [],
            'properties' => [],
            'developers' => [],
            'cities' => [],
        ];
    }
}
