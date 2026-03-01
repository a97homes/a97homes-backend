<?php

namespace App\Actions\SellUnit;

use App\Models\SellUnit;

class StoreSellUnitAction
{
    public function execute(array $data): SellUnit
    {
        return SellUnit::create($data);
    }
}
