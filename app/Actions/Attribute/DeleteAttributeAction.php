<?php

namespace App\Actions\Attribute;

use App\Models\Attribute;

class DeleteAttributeAction
{
    public function execute(Attribute $attribute): bool
    {
        return $attribute->delete();
    }
}
