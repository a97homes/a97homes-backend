<?php

namespace App\Actions\Country;

use App\Models\Country;

class DeleteCountryAction
{
    public function execute(Country $country): bool
    {
        $country->clearMediaCollection(Country::MEDIA_COLLECTION_FLAG);

        return $country->delete();
    }
}
