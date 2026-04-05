<?php

namespace App\Actions\Attribute;

use App\Models\Attribute;

class UpdateAttributeAction
{
    public function execute(Attribute $attribute, array $data): Attribute
    {
        $attribute->update($data);

        return $attribute;
    }
}
