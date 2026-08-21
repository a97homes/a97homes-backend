<?php

namespace App\Http\Resources\API\V1\Consultant;

use App\Models\ConsultantPhone;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultantPhoneResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ConsultantPhone $phone */
        $phone = $this->resource;

        return [
            'id' => $phone->id,
            'phone' => $phone->phone,
        ];
    }
}
