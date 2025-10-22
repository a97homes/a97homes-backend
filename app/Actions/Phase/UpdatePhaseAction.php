<?php

namespace App\Actions\Phase;

use App\Models\Phase;

class UpdatePhaseAction
{
    public function execute(Phase $phase, array $data): Phase
    {
        $phase->update($data);

        return $phase;
    }
}
