<?php

namespace App\Actions\ContactMethod;

use App\Enums\ContactMethodTypeEnum;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SyncContactMethodsAction
{
    /**
     * @param  array<int, array<string, mixed>>|null  $phones
     * @param  array<int, array<string, mixed>>|null  $whatsappNumbers
     */
    public function execute(Model $contactable, ?array $phones = null, ?array $whatsappNumbers = null): void
    {
        DB::transaction(function () use ($contactable, $phones, $whatsappNumbers): void {
            if ($phones !== null) {
                $this->syncType($contactable, ContactMethodTypeEnum::Phone, $phones);
            }

            if ($whatsappNumbers !== null) {
                $this->syncType($contactable, ContactMethodTypeEnum::Whatsapp, $whatsappNumbers);
            }
        });
    }

    /**
     * @param  array<int, array<string, mixed>>  $contacts
     */
    private function syncType(Model $contactable, ContactMethodTypeEnum $type, array $contacts): void
    {
        $contactable->contactMethods()
            ->where('type', $type->value)
            ->delete();

        $contacts = $this->normalizePrimaryFlags($contacts);

        foreach ($contacts as $index => $contact) {
            $contactable->contactMethods()->create([
                'type' => $type->value,
                'country_code' => $contact['country_code'],
                'number' => $contact['number'],
                'is_primary' => (bool) ($contact['is_primary'] ?? false),
                'sort_order' => (int) ($contact['sort_order'] ?? $index),
            ]);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $contacts
     * @return array<int, array<string, mixed>>
     */
    private function normalizePrimaryFlags(array $contacts): array
    {
        if ($contacts === []) {
            return [];
        }

        $primaryIndex = null;

        foreach ($contacts as $index => $contact) {
            if (($contact['is_primary'] ?? false) && $primaryIndex === null) {
                $primaryIndex = $index;
            }
        }

        $primaryIndex ??= array_key_first($contacts);

        foreach ($contacts as $index => $contact) {
            $contacts[$index]['is_primary'] = $index === $primaryIndex;
        }

        return $contacts;
    }
}
