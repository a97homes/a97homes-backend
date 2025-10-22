<?php

namespace App\Actions\Developer;

use App\Models\Developer;

class UpdateDeveloperAction
{
    public function execute(Developer $developer, array $data): Developer
    {
        $developer->update($data);

        return $developer;
    }
}
