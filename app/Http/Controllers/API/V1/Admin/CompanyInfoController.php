<?php

namespace App\Http\Controllers\API\V1\Admin;

use App\Enums\Role\UserRoleEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\API\V1\Admin\CompanyInfo\UpdateCompanyInfoRequest;
use App\Http\Resources\API\V1\CompanyInfo\CompanyInfoResource;
use App\Models\CompanyInfo;
use App\Permissions\PermissionRegistry;
use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Spatie\Permission\Middleware\RoleOrPermissionMiddleware;

class CompanyInfoController extends Controller implements HasMiddleware
{
    public static function middleware(): array
    {
        return [
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_COMPANY_INFO_SHOW]), only: ['show']),
            new Middleware(RoleOrPermissionMiddleware::using([UserRoleEnum::ADMIN->value, PermissionRegistry::ADMIN_COMPANY_INFO_UPDATE]), only: ['update']),
        ];
    }

    public function show(): JsonResponse
    {
        $companyInfo = CompanyInfo::current();

        return $this->ok(data: CompanyInfoResource::make($companyInfo));
    }

    public function update(UpdateCompanyInfoRequest $request): JsonResponse
    {
        $companyInfo = CompanyInfo::current();
        $companyInfo->update($request->validated());

        return $this->ok(
            message: __('messages.company_info_updated_successfully'),
            data: CompanyInfoResource::make($companyInfo),
        );
    }
}
