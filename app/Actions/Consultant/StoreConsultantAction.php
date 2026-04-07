<?php

namespace App\Actions\Consultant;

use App\Models\Consultant;

class StoreConsultantAction
{
    public function execute(array $data): Consultant
    {
        $phones = $data['phones'] ?? [];
        unset($data['phones']);

        $consultant = Consultant::create($data);

        foreach ($phones as $phone) {
            $consultant->phones()->create(['phone' => $phone]);
        }

        return $consultant->load('phones');
    }
}
