<?php

use App\Support\ContactMethodNormalizer;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('contact_methods', function (Blueprint $table) {
            $table->id();
            $table->morphs('contactable');
            $table->string('type', 30);
            $table->string('country_code', 10);
            $table->string('number', 30);
            $table->boolean('is_primary')->default(false);
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['contactable_type', 'contactable_id', 'type', 'sort_order'], 'contact_methods_owner_type_sort_idx');
            $table->index(['type', 'country_code', 'number'], 'contact_methods_type_number_idx');
            $table->unique(['contactable_type', 'contactable_id', 'type', 'country_code', 'number'], 'contact_methods_owner_type_number_unique');
        });

        $this->createPrimaryIndex();
        $this->migrateDeveloperContacts();
    }

    public function down(): void
    {
        Schema::dropIfExists('contact_methods');
    }

    private function createPrimaryIndex(): void
    {
        $driver = Schema::getConnection()->getDriverName();

        if ($driver === 'pgsql') {
            DB::statement('CREATE UNIQUE INDEX contact_methods_one_primary_per_type ON contact_methods (contactable_type, contactable_id, type) WHERE is_primary = true');
        } elseif ($driver === 'sqlite') {
            DB::statement('CREATE UNIQUE INDEX contact_methods_one_primary_per_type ON contact_methods (contactable_type, contactable_id, type) WHERE is_primary = 1');
        }
    }

    private function migrateDeveloperContacts(): void
    {
        if (! Schema::hasTable('developers')) {
            return;
        }

        DB::table('developers')
            ->select(['id', 'phone', 'whatsapp'])
            ->orderBy('id')
            ->chunkById(100, function ($developers): void {
                foreach ($developers as $developer) {
                    $this->insertLegacyContact((int) $developer->id, 'phone', $developer->phone ?? null);
                    $this->insertLegacyContact((int) $developer->id, 'whatsapp', $developer->whatsapp ?? null);
                }
            });
    }

    private function insertLegacyContact(int $developerId, string $type, ?string $value): void
    {
        $contact = ContactMethodNormalizer::splitLegacy($value);

        if ($contact === null) {
            return;
        }

        DB::table('contact_methods')->insertOrIgnore([
            'contactable_type' => 'developer',
            'contactable_id' => $developerId,
            'type' => $type,
            'country_code' => $contact['country_code'],
            'number' => $contact['number'],
            'is_primary' => true,
            'sort_order' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }
};
