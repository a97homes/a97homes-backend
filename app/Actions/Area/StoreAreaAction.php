<?php

namespace App\Actions\Area;

use App\Models\Area;

class StoreAreaAction
{
    public function execute(array $data): Area
    {
        return Area::create($data);
    }
}
