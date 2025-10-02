<?php

namespace App\Actions\Unit;

use App\Models\Unit;

class DeleteUnitAction
{
    public function execute(Unit $unit): bool
    {
        return $unit->delete();
    }
}
