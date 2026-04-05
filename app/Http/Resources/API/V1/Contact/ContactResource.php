<?php

namespace App\Http\Resources\API\V1\Contact;

use App\Http\Resources\City\CityResource;
use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ContactResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        /** @var Contact $contact */
        $contact = $this->resource;

        return [
            'id' => $this->id,
            'email' => $this->whenHas('email', fn () => $contact->email),
            'name' => $this->whenHas('id', fn () => $contact->name),
            'phone' => $this->whenHas('phone', fn () => $contact->phone),
            'city' => CityResource::make($this->whenLoaded('city')),
            'message' => $this->whenHas('message', fn () => $contact->message),
            'created_at' => $this->whenHas('created_at', fn () => $contact->created_at),
        ];
    }
}
