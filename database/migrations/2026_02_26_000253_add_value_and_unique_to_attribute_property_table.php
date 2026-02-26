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
        Schema::table('attribute_property', function (Blueprint $table) {
            $table->text('value')->nullable()->after('property_id');
            $table->unique(['property_id', 'attribute_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('attribute_property', function (Blueprint $table) {
            $table->dropUnique(['property_id', 'attribute_id']);
            $table->dropColumn('value');
        });
    }
};
