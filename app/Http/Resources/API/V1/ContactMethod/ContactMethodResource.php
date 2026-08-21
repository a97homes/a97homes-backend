<?php

namespace App\Http\Resources\API\V1\ContactMethod;

use App\Models\ContactMethod;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactMethodResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var ContactMethod $contactMethod */
        $contactMethod = $this->resource;

        return [
            'country_code' => $contactMethod->country_code,
            'number' => $contactMethod->number,
            'is_primary' => $contactMethod->is_primary,
            'sort_order' => $contactMethod->sort_order,
        ];
    }
}
