<?php

namespace App\Actions\Developer;

use App\Models\Developer;

class StoreDeveloperAction
{
    public function execute(array $data): Developer
    {
        return Developer::create($data);
    }
}
