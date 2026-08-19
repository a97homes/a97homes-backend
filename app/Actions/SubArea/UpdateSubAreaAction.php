<?php

namespace App\Actions\SubArea;

use App\Models\SubArea;

class UpdateSubAreaAction
{
    public function execute(SubArea $subArea, array $data): SubArea
    {
        $subArea->update($data);

        return $subArea;
    }
}
