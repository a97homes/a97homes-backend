<?php

namespace App\Http\Resources\API\V1\Country;

use App\Models\Country;
use App\Traits\HasTranslatableFields;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CountryResource extends JsonResource
{
    use HasTranslatableFields;

    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var Country $country */
        $country = $this->resource;

        return [
            'id' => $this->whenHas('id', fn () => $country->id),
            'name' => $this->whenHas('name', fn () => $this->getTranslatableField($country, 'name')),
            'code' => $this->whenHas('code', fn () => $country->code),
            'flag' => $country->flag_url,
            'phone_code' => $this->whenHas('phone_code', fn () => $country->phone_code),
            'created_at' => $this->whenHas('created_at', fn () => $country->created_at),
        ];
    }
}
