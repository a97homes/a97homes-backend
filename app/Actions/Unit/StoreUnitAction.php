<?php

namespace App\Actions\Unit;

use App\Models\Unit;

class StoreUnitAction
{
    public function execute(array $data): Unit
    {
        return Unit::create($data);
    }
}
