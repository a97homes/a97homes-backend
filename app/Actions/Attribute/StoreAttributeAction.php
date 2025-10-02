<?php

namespace App\Actions\Attribute;

use App\Models\Attribute;

class StoreAttributeAction
{
    public function execute(array $data): Attribute
    {
        return Attribute::create($data);
    }
}
