<?php

declare(strict_types=1);

namespace App\Actions\Faq;

use App\Models\Faq;

class UpdateFaqAction
{
    public function execute(Faq $faq, array $data): Faq
    {
        $faq->update($data);

        return $faq;
    }
}
