<?php

namespace App\Http\Resources\API\V1\Order;

use App\Http\Resources\API\V1\Property\PropertyResource;
use App\Http\Resources\API\V1\User\UserResource;
use App\Http\Resources\SubArea\SubAreaResource;
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
    {   /** var Order $order */
        $order = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $this->id),
            'status' => $this->whenHas('status', fn () => $this->status),
            'user' => UserResource::make($this->whenLoaded('user')),
            'name' => $this->whenHas('id', fn () => $order->name),
            'phone' => $this->whenHas('phone', fn () => $order->phone),
            'description' => $this->whenHas('message', fn () => $order->message),
            'sub_area' => SubAreaResource::make($this->whenLoaded('subArea')),
            'propertyType' => PropertyResource::make($this->whenLoaded('propertyType')),
            'created_at' => $this->whenHas('created_at', fn () => $order->created_at),
        ];
    }
}
