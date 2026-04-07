<?php

namespace App\Http\Resources\API\V1\Consultant;

use App\Models\Consultant;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConsultantResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Consultant $consultant */
        $consultant = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $consultant->id),
            'name' => $this->whenHas('name', fn () => $consultant->name),
            'job_title' => $this->whenHas('job_title', fn () => $consultant->job_title),
            'sales_percentage' => $this->whenHas('sales_percentage', fn () => $consultant->sales_percentage),
            'image' => $this->whenHas('image', fn () => $consultant->image),
            'is_featured' => $this->whenHas('is_featured', fn () => $consultant->is_featured),
            'phones' => ConsultantPhoneResource::collection($this->whenLoaded('phones')),
            'created_at' => $this->whenHas('created_at', fn () => $consultant->created_at),
        ];
    }
}
