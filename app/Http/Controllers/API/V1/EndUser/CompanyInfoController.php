<?php

namespace App\Http\Controllers\API\V1\EndUser;

use App\Http\Controllers\Controller;
use App\Http\Resources\API\V1\CompanyInfo\CompanyInfoResource;
use App\Models\CompanyInfo;
use Illuminate\Http\JsonResponse;

class CompanyInfoController extends Controller
{
    public function show(): JsonResponse
    {
        $companyInfo = CompanyInfo::current();

        return $this->ok(data: CompanyInfoResource::make($companyInfo));
    }
}
