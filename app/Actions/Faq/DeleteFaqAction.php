<?php

declare(strict_types=1);

namespace App\Actions\Faq;

use App\Models\Faq;

class DeleteFaqAction
{
    public function execute(Faq $faq): void
    {
        $faq->delete();
    }
}
