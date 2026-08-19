<?php

namespace App\Actions\Compound;

use App\Actions\ContactMethod\SyncContactMethodsAction;
use App\Models\Compound;

class StoreCompoundAction
{
    public function __construct(private readonly SyncContactMethodsAction $syncContactMethodsAction) {}

    public function execute(array $data): Compound
    {
        $phones = $data['phones'] ?? null;
        $whatsappNumbers = $data['whatsapp_numbers'] ?? null;
        unset($data['phones'], $data['whatsapp_numbers']);

        $compound = Compound::create($data);
        $this->syncContactMethodsAction->execute($compound, $phones, $whatsappNumbers);

        return $compound->load(['phones', 'whatsappNumbers']);
    }
}
