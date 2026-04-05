<?php

namespace App\Actions\Unit;

use App\Models\Unit;

class UpdateUnitAction
{
    public function execute(Unit $unit, array $data): Unit
    {
        $unit->update($data);

        return $unit;
    }
}
