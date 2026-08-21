<?php

namespace App\Actions\Compound;

use App\Actions\ContactMethod\SyncContactMethodsAction;
use App\Models\Compound;

class UpdateCompoundAction
{
    public function __construct(private readonly SyncContactMethodsAction $syncContactMethodsAction) {}

    public function execute(Compound $compound, array $data): Compound
    {
        $phones = array_key_exists('phones', $data) ? $data['phones'] : null;
        $whatsappNumbers = array_key_exists('whatsapp_numbers', $data) ? $data['whatsapp_numbers'] : null;
        unset($data['phones'], $data['whatsapp_numbers']);

        $compound->update($data);
        $this->syncContactMethodsAction->execute($compound, $phones, $whatsappNumbers);

        return $compound->load(['phones', 'whatsappNumbers']);
    }
}
