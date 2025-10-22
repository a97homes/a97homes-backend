<?php

namespace App\Actions\Phase;

use App\Models\Phase;

class DeletePhaseAction
{
    public function execute(Phase $phase): bool
    {
        return $phase->delete();
    }
}
