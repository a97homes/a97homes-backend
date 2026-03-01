<?php

namespace App\Http\Resources\API\V1\CompanyInfo;

use App\Models\CompanyInfo;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyInfoResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var CompanyInfo $companyInfo */
        $companyInfo = $this->resource;

        return [
            'id' => $companyInfo->id,
            'phone' => $companyInfo->phone,
            'email' => $companyInfo->email,
            'working_hours' => $companyInfo->working_hours,
            'address' => $companyInfo->address,
            'updated_at' => $companyInfo->updated_at,
        ];
    }
}
