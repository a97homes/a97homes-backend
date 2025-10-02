<?php

namespace App\Actions\City;

use App\Models\City;

class StoreCityAction
{
    public function execute(array $data): City
    {
        return City::create($data);
    }
}
