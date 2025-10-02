<?php

namespace App\Actions\Country;

use App\Models\Country;

class StoreCountryAction
{
    public function execute(array $data): Country
    {
        return Country::create($data);
    }
}
