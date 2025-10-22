<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Actions\Project\DeleteProjectAction;
use App\Actions\Project\StoreProjectAction;
use App\Actions\Project\UpdateProjectAction;
use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\Project\StoreProjectRequest;
use App\Http\Requests\API\V1\Admin\Project\UpdateProjectRequest;
use App\Http\Resources\API\V1\Project\ProjectCollection;
use App\Http\Resources\API\V1\Project\ProjectResource;
use App\Models\Project;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\QueryBuilder;

class ProjectController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROJECTS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROJECTS_STORE]), only: ['store']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROJECTS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROJECTS_UPDATE]), only: ['update']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_PROJECTS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $projects = QueryBuilder::for(Project::class)
            ->allowedFilters([
                AllowedFilter::exact('developer_id'),
                AllowedFilter::partial('name'),
                AllowedFilter::scope('created_from'),
                AllowedFilter::scope('created_to'),
            ])
            ->defaultSort('-id')
            ->allowedSorts(['id', 'name'])
            ->macroPaginate();

        return $this->ok(data: new ProjectCollection($projects));
    }

    public function store(StoreProjectRequest $request, StoreProjectAction $action): JsonResponse
    {
        $project = $action->execute($request->validated());

        return $this->ok(message: __('messages.project_created_successfully'), data: ProjectResource::make($project));
    }

    public function show(Project $project): JsonResponse
    {
        return $this->ok(data: ProjectResource::make($project->load('developer:id,name')));
    }

    public function update(UpdateProjectRequest $request, Project $project, UpdateProjectAction $action): JsonResponse
    {
        $action->execute($project, $request->validated());

        return $this->ok(message: __('messages.project_updated_successfully'), data: ProjectResource::make($project));
    }

    public function destroy(Project $project, DeleteProjectAction $action): JsonResponse
    {
        $action->execute($project);

        return $this->ok(message: __('messages.project_deleted_successfully'));
    }

    public function dropdown(): JsonResponse
    {
        $projects = Project::select('id', 'name')->get();

        return $this->ok(data: ProjectResource::collection($projects));
    }
}
