<?php

namespace App\Actions\Country;

use App\Models\Country;

class DeleteCountryAction
{
    public function execute(Country $country): bool
    {
        return $country->delete();
    }
}
