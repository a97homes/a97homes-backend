<?php

declare(strict_types=1);

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\Admin\ConsultantReview\AdminConsultantReviewCollection;
use App\Http\Resources\API\V1\Admin\ConsultantReview\AdminConsultantReviewResource;
use App\Models\ConsultantReview;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;
use Spatie\QueryBuilder\AllowedFilter;
use Spatie\QueryBuilder\AllowedSort;
use Spatie\QueryBuilder\QueryBuilder;

class ConsultantReviewController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CONSULTANT_REVIEWS_INDEX]), only: ['index']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CONSULTANT_REVIEWS_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_CONSULTANT_REVIEWS_DESTROY]), only: ['destroy']),
        ];
    }

    public function index(): JsonResponse
    {
        $reviews = QueryBuilder::for(ConsultantReview::class)
            ->allowedFilters([
                AllowedFilter::exact('consultant_id'),
                AllowedFilter::exact('user_id'),
                AllowedFilter::exact('overall_rating'),
            ])
            ->defaultSort('-id')
            ->allowedSorts([
                AllowedSort::field('id'),
                AllowedSort::field('overall_rating'),
                AllowedSort::field('created_at'),
            ])
            ->with(['consultant:id,name', 'user:id,name,email'])
            ->macroPaginate();

        return $this->ok(data: new AdminConsultantReviewCollection($reviews));
    }

    public function show(ConsultantReview $consultantReview): JsonResponse
    {
        return $this->ok(data: AdminConsultantReviewResource::make(
            $consultantReview->load(['consultant:id,name', 'user:id,name,email'])
        ));
    }

    public function destroy(ConsultantReview $consultantReview): JsonResponse
    {
        $consultantReview->delete();

        return $this->ok(message: __('messages.consultant_review_deleted_successfully'));
    }
}
