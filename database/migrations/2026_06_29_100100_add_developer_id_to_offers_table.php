<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->foreignId('developer_id')->nullable()->after('compound_id')->constrained('developers')->cascadeOnDelete();
            $table->index(['developer_id', 'is_active']);
        });

        Schema::table('offers', function (Blueprint $table) {
            $table->foreignId('compound_id')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('offers', function (Blueprint $table) {
            $table->dropIndex(['developer_id', 'is_active']);
            $table->dropConstrainedForeignId('developer_id');
        });
    }
};
