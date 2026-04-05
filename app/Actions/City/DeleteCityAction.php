<?php

namespace App\Actions\City;

use App\Models\City;

class DeleteCityAction
{
    public function execute(City $city): bool
    {

        return $city->delete();
    }
}
