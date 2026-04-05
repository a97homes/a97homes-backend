<?php

namespace App\Actions\Compound;

use App\Models\Compound;

class StoreCompoundAction
{
    public function execute(array $data): Compound
    {
        return Compound::create($data);
    }
}
