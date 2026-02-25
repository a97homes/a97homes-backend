<?php

namespace App\Actions\Developer;

use App\Models\Developer;

class DeleteDeveloperAction
{
    public function execute(Developer $developer): bool
    {
        return $developer->delete();
    }
}
