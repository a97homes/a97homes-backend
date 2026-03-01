<?php

namespace App\Actions\Compound;

use App\Models\Compound;

class DeleteCompoundAction
{
    public function execute(Compound $compound): bool
    {
        return $compound->delete();
    }
}
