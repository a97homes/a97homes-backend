<?php

namespace App\Actions\Developer;

use App\Actions\ContactMethod\SyncContactMethodsAction;
use App\Models\Developer;

class UpdateDeveloperAction
{
    public function __construct(private readonly SyncContactMethodsAction $syncContactMethodsAction) {}

    public function execute(Developer $developer, array $data): Developer
    {
        $logo = $data['logo'] ?? null;
        $banner = $data['banner'] ?? null;
        $phones = array_key_exists('phones', $data) ? $data['phones'] : null;
        $whatsappNumbers = array_key_exists('whatsapp_numbers', $data) ? $data['whatsapp_numbers'] : null;
        unset($data['logo'], $data['banner'], $data['phones'], $data['whatsapp_numbers'], $data['phone'], $data['whatsapp']);

        $developer->update($data);
        $this->syncContactMethodsAction->execute($developer, $phones, $whatsappNumbers);

        if ($logo) {
            $developer->clearMediaCollection(Developer::MEDIA_COLLECTION_LOGO);
            $developer->addMedia($logo)->toMediaCollection(Developer::MEDIA_COLLECTION_LOGO);
        }

        if ($banner) {
            $developer->clearMediaCollection(Developer::MEDIA_COLLECTION_BANNER);
            $developer->addMedia($banner)->toMediaCollection(Developer::MEDIA_COLLECTION_BANNER);
        }

        return $developer->load(['phones', 'whatsappNumbers']);
    }
}
