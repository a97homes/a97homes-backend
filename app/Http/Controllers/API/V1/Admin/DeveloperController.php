<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Developer\BulkDeleteDevelopersAction;
use App\Actions\Developer\BulkUpdateDeveloperStatusAction;
use App\Actions\Developer\DeleteDeveloperAction;
use App\Actions\Developer\StoreDeveloperAction;
use App\Actions\Developer\UpdateDeveloperAction;
use App\Enums\Role\UserRoleEnum;
use App\Filters\NameFilter;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Developer\BulkDeleteDeveloperRequest;
use App\Http\Requests\API\V1\Admin\Developer\BulkUpdateDeveloperStatusRequest;
use App\Http\Requests\API\V1\Admin\Developer\StoreDeveloperRequest;
use App\Http\Requests\API\V1\Admin\Developer\UpdateDeveloperRequest;
use App\Http\Resources\API\V1\Developer\DeveloperCollection;
use App\Http\Resources\API\V1\Developer\DeveloperResource;
use App\Models\Compound;
use App\Models\Developer;
use App\Models\SubArea;
use App\Permissions\PermissionRegistry;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class DeveloperController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_DEVELOPERS_INDEX]), only: ['index', 'dropdown']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_DEVELOPERS_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_DEVELOPERS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_DEVELOPERS_UPDATE]), only: ['update', 'toggleActive', 'bulkUpdateStatus']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_DEVELOPERS_DESTROY]), only: ['destroy', 'bulkDestroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $developers = QueryBuilder::for(Developer::class)
            ->with(['subAreas.area:id,name', 'phones', 'whatsappNumbers'])
            ->withCount([
                'compounds',
                'properties as units_count',
            ])
            ->addSelect([
                'sub_areas_count' => Compound::query()
                    ->selectRaw('COUNT(DISTINCT sub_area_id)')
                    ->whereColumn('developer_id', 'developers.id'),
            ])
            ->allowedFilters([
                AllowedFilter::custom('name', new NameFilter),
                AllowedFilter::callback('phone', function (Builder $query, $value): void {
                    $query->where(function (Builder $inner) use ($value): void {
                        $inner->where('phone', 'LIKE', "%{$value}%")
                            ->orWhereHas('phones', fn (Builder $phones) => $phones
                                ->where('number', 'LIKE', "%{$value}%")
                                ->orWhere('country_code', 'LIKE', "%{$value}%"));
                    });
                }),
                AllowedFilter::callback('whatsapp', function (Builder $query, $value): void {
                    $query->where(function (Builder $inner) use ($value): void {
                        $inner->where('whatsapp', 'LIKE', "%{$value}%")
                            ->orWhereHas('whatsappNumbers', fn (Builder $whatsappNumbers) => $whatsappNumbers
                                ->where('number', 'LIKE', "%{$value}%")
                                ->orWhere('country_code', 'LIKE', "%{$value}%"));
                    });
                }),
                AllowedFilter::exact('id'),
                AllowedFilter::exact('is_active'),
                AllowedFilter::scope('sub_area_id'),
                AllowedFilter::scope('area_id'),
                AllowedFilter::scope('property_type_id'),
                AllowedFilter::scope('sale_type'),
                AllowedFilter::scope('completion_status'),
                AllowedFilter::scope('min_compounds'),
                AllowedFilter::scope('max_compounds'),
                AllowedFilter::scope('min_units'),
                AllowedFilter::scope('max_units'),
                AllowedFilter::scope('min_sub_areas'),
                AllowedFilter::scope('max_sub_areas'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                'id',
                'name',
                'created_at',
                'is_active',
                AllowedSort::field('compounds_count'),
                AllowedSort::field('units_count'),
                AllowedSort::field('sub_areas_count'),
            ])
            ->macroPaginate();

        $this->attachSubAreaStats($developers->getCollection());

        return $this->ok(data: new DeveloperCollection($developers));
    }

    private function attachSubAreaStats(Collection $developers): void
    {
        $developerIds = $developers->pluck('id')->filter()->values();
        $subAreaIds = $developers
            ->flatMap(fn (Developer $developer) => $developer->subAreas->pluck('id'))
            ->filter()
            ->unique()
            ->values();

        if ($developerIds->isEmpty() || $subAreaIds->isEmpty()) {
            return;
        }

        $compoundCounts = Compound::query()
            ->select('developer_id', 'sub_area_id')
            ->selectRaw('COUNT(*) as compounds_count')
            ->whereIn('developer_id', $developerIds)
            ->whereIn('sub_area_id', $subAreaIds)
            ->groupBy('developer_id', 'sub_area_id')
            ->get()
            ->keyBy(fn (Compound $compound): string => $compound->developer_id.'-'.$compound->sub_area_id);

        $unitCounts = DB::table('compounds')
            ->leftJoin('properties', 'properties.compound_id', '=', 'compounds.id')
            ->select('compounds.developer_id', 'compounds.sub_area_id')
            ->selectRaw('COUNT(properties.id) as units_count')
            ->whereIn('compounds.developer_id', $developerIds)
            ->whereIn('compounds.sub_area_id', $subAreaIds)
            ->groupBy('compounds.developer_id', 'compounds.sub_area_id')
            ->get()
            ->keyBy(fn ($row): string => $row->developer_id.'-'.$row->sub_area_id);

        $developers->each(function (Developer $developer) use ($compoundCounts, $unitCounts): void {
            $developer->subAreas->each(function (SubArea $subArea) use ($developer, $compoundCounts, $unitCounts): void {
                $key = $developer->id.'-'.$subArea->id;

                $subArea->setAttribute('developer_compounds_count', (int) ($compoundCounts->get($key)?->compounds_count ?? 0));
                $subArea->setAttribute('developer_units_count', (int) ($unitCounts->get($key)?->units_count ?? 0));
            });
        });
    }

    public function store(StoreDeveloperRequest $request, StoreDeveloperAction $action): JsonResponse
    {
        $developer = $action->execute($request->validated());

        return $this->ok(message: __('messages.developer_created_successfully'), data: DeveloperResource::make($developer));
    }

    public function show(Developer $developer): JsonResponse
    {
        $developer->load([
            'media',
            'subAreas.area:id,name',
            'offers',
            'faqs',
            'phones',
            'whatsappNumbers',
        ]);

        $developer->loadCount([
            'compounds',
            'properties as units_count',
        ]);

        $this->attachSubAreaStats(collect([$developer]));

        return $this->ok(data: DeveloperResource::make($developer));
    }

    public function update(UpdateDeveloperRequest $request, Developer $developer, UpdateDeveloperAction $action): JsonResponse
    {
        $action->execute($developer, $request->validated());

        return $this->ok(message: __('messages.developer_updated_successfully'), data: DeveloperResource::make($developer));
    }

    public function destroy(Developer $developer, DeleteDeveloperAction $action): JsonResponse
    {
        $action->execute($developer);

        return $this->ok(message: __('messages.developer_deleted_successfully'));
    }

    /**
     * Delete several developers in one request.
     */
    public function bulkDestroy(BulkDeleteDeveloperRequest $request, BulkDeleteDevelopersAction $action): JsonResponse
    {
        $deletedCount = $action->execute($request->developerIds());

        return $this->ok(
            message: __('messages.developers_deleted_successfully', ['count' => $deletedCount]),
            data: ['deleted_count' => $deletedCount],
        );
    }

    /**
     * Activate or deactivate several developers in one request.
     */
    public function bulkUpdateStatus(BulkUpdateDeveloperStatusRequest $request, BulkUpdateDeveloperStatusAction $action): JsonResponse
    {
        $isActive = $request->isActive();
        $updatedCount = $action->execute($request->developerIds(), $isActive);

        return $this->ok(
            message: __($isActive ? 'messages.developers_activated' : 'messages.developers_deactivated', ['count' => $updatedCount]),
            data: ['updated_count' => $updatedCount],
        );
    }

    public function toggleActive(Developer $developer): JsonResponse
    {
        $developer->update(['is_active' => ! $developer->is_active]);

        return $this->ok(
            message: __($developer->is_active ? 'messages.developer_activated' : 'messages.developer_deactivated'),
            data: DeveloperResource::make($developer->refresh()),
        );
    }

    public function dropdown(): JsonResponse
    {
        $developers = Developer::select('id', 'name')->with(['phones', 'whatsappNumbers'])->get();

        return $this->ok(data: DeveloperResource::collection($developers));
    }
}
