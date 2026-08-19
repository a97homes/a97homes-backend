<?php

namespace App\Actions\SubArea;

use App\Models\SubArea;

class StoreSubAreaAction
{
    public function execute(array $data): SubArea
    {
        return SubArea::create($data);
    }
}
