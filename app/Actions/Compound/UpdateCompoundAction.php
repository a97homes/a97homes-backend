<?php

namespace App\Actions\Compound;

use App\Models\Compound;

class UpdateCompoundAction
{
    public function execute(Compound $compound, array $data): Compound
    {
        $compound->update($data);

        return $compound;
    }
}
