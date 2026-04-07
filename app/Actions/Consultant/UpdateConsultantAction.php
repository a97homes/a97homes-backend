<?php

namespace App\Actions\Consultant;

use App\Models\Consultant;

class UpdateConsultantAction
{
    public function execute(Consultant $consultant, array $data): Consultant
    {
        $phones = $data['phones'] ?? null;
        unset($data['phones']);

        $consultant->update($data);

        if ($phones !== null) {
            $consultant->phones()->delete();

            foreach ($phones as $phone) {
                $consultant->phones()->create(['phone' => $phone]);
            }
        }

        return $consultant->load('phones');
    }
}
