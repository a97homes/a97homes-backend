<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Admin\CompoundReview\AdminCompoundReviewCollection;
use App\Http\Resources\API\V1\Admin\CompoundReview\AdminCompoundReviewResource;
use App\Models\CompoundReview;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class CompoundReviewController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_COMPOUND_REVIEWS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_COMPOUND_REVIEWS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_COMPOUND_REVIEWS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $reviews = QueryBuilder::for(CompoundReview::class)
            ->allowedFilters([
                AllowedFilter::exact('compound_id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('overall_rating'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('overall_rating'),
                AllowedSort::field('created_at'),
            ])
            ->with(['compound:id,name', 'user:id,name,email'])
            ->macroPaginate();

        return $this->ok(data: new AdminCompoundReviewCollection($reviews));
    }

    public function show(CompoundReview $compoundReview): JsonResponse
    {
        return $this->ok(data: AdminCompoundReviewResource::make(
            $compoundReview->load(['compound:id,name', 'user:id,name,email'])
        ));
    }

    public function destroy(CompoundReview $compoundReview): JsonResponse
    {
        $compoundReview->delete();

        return $this->ok(message: __('messages.compound_review_deleted_successfully'));
    }
}
