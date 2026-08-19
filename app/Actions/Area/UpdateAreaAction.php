<?php

namespace App\Actions\State;

use App\Models\State;

class UpdateStateAction
{
    public function execute(State $state, array $data): State
    {
        $state->update($data);

        return $state;
    }
}
