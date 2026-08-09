<?php

declare(strict_types=1);

namespace App\Http\Resources\API\V1\SavedSearch;

use App\Models\SavedSearch;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class SavedSearchResource extends JsonResource
{
    /**
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var SavedSearch $search */
        $search = $this->resource;

        return [
            'id' => $search->id,
            'name' => $search->name,
            'type' => $search->type?->value,
            'criteria' => $search->criteria,
            'notify_by_email' => $search->notify_by_email,
            'last_checked_at' => $search->last_checked_at,
            'created_at' => $search->created_at,
            'updated_at' => $search->updated_at,
        ];
    }
}
