<?php

declare(strict_types=1);

namespace App\Actions\Faq;

use App\Models\Faq;

class StoreFaqAction
{
    public function execute(array $data): Faq
    {
        return Faq::create($data);
    }
}
