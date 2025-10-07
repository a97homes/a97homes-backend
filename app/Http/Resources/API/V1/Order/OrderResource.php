<?php

namespace App\Http\Resources\API\V1\Order;

use App\Http\Resources\API\V1\User\UserResource;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class OrderResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->whenHas('id', fn () => $this->id),
            'status' => $this->whenHas('status', fn () => $this->status),
            'user' => UserResource::make($this->whenLoaded('user')),
        ];
    }
}
