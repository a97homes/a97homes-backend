<?php

namespace App\Actions\Phase;

use App\Models\Phase;

class StorePhaseAction
{
    public function execute(array $data): Phase
    {
        return Phase::create($data);
    }
}
