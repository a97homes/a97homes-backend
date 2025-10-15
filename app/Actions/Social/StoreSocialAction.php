<?php

namespace App\Actions\Social;

use App\Models\Social;

class StoreSocialAction
{
    public function execute(array $data)
    {
        return Social::create($data);
    }
}
