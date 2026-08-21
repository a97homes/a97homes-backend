<?php

namespace App\Actions\Area;

use App\Models\Area;

class UpdateAreaAction
{
    public function execute(Area $area, array $data): Area
    {
        $area->update($data);

        return $area;
    }
}
