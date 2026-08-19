<?php

namespace App\Actions\Area;

use App\Models\Area;

class DeleteAreaAction
{
    public function execute(Area $area): bool
    {
        return $area->delete();
    }
}
