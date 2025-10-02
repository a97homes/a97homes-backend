<?php

namespace App\Actions\Country;

use App\Models\Country;

class UpdateCountryAction
{
    public function execute(Country $country, array $data): Country
    {
        $country->update($data);

        return $country;
    }
}
