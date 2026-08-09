<?php

namespace App\Actions\Consultant;

use App\Models\Consultant;
use App\Models\ConsultantReview;

class StoreConsultantReviewAction
{
    public function handle(Consultant $consultant, array $data): ConsultantReview
    {
        return $consultant->reviews()->create($data);
    }
}
