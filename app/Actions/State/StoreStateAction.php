<?php

namespace App\Actions\State;

use App\Models\State;

class StoreStateAction
{
    public function execute(array $data): State
    {
        return State::create($data);
    }
}
