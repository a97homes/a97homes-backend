<?php

namespace App\Actions\SubArea;

use App\Models\SubArea;

class DeleteSubAreaAction
{
    public function execute(SubArea $subArea): bool
    {

        return $subArea->delete();
    }
}
