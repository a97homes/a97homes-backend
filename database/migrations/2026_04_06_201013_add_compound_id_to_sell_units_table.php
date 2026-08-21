<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('sell_units', function (Blueprint $table) {
            $table->foreignId('compound_id')->nullable()->after('property_type_id')->constrained('compounds')->nullOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('sell_units', function (Blueprint $table) {
            $table->dropConstrainedForeignId('compound_id');
        });
    }
};
