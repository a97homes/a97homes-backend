<?php

namespace App\Actions\Consultant;

use App\Models\Consultant;

class DeleteConsultantAction
{
    public function execute(Consultant $consultant): bool
    {
        return $consultant->delete();
    }
}
