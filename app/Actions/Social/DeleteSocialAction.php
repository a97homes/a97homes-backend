<?php

namespace App\Actions\Social;

use App\Models\Social;

class DeleteSocialAction
{
    public function execute(Social $social): bool
    {
        return $social->delete();
    }
}
