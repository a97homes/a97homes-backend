<?php

namespace App\Actions\Developer;

use App\Actions\ContactMethod\SyncContactMethodsAction;
use App\Models\Developer;

class StoreDeveloperAction
{
    public function __construct(private readonly SyncContactMethodsAction $syncContactMethodsAction) {}

    public function execute(array $data): Developer
    {
        $logo = $data['logo'] ?? null;
        $banner = $data['banner'] ?? null;
        $phones = $data['phones'] ?? null;
        $whatsappNumbers = $data['whatsapp_numbers'] ?? null;
        unset($data['logo'], $data['banner'], $data['phones'], $data['whatsapp_numbers'], $data['phone'], $data['whatsapp']);

        $developer = Developer::create($data);
        $this->syncContactMethodsAction->execute($developer, $phones, $whatsappNumbers);

        if ($logo) {
            $developer->addMedia($logo)->toMediaCollection(Developer::MEDIA_COLLECTION_LOGO);
        }

        if ($banner) {
            $developer->addMedia($banner)->toMediaCollection(Developer::MEDIA_COLLECTION_BANNER);
        }

        return $developer->load(['phones', 'whatsappNumbers']);
    }
}
