<?php

namespace App\Actions\State;

use App\Models\State;

class DeleteStateAction
{
    public function execute(State $state): void
    {
        $state->delete();
    }
}
