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
        Schema::create('properties', function (Blueprint $table) {
            $table->id();
            $table->json('name')->nullable();
            $table->foreignId('city_id')->nullable()->constrained('cities')->cascadeOnDelete();
            $table->foreignId('property_type_id')->nullable()->constrained('property_types')->cascadeOnDelete();
            $table->foreignId('order_id')->nullable()->constrained('orders')->cascadeOnDelete();
            $table->string('status');
            $table->string('address');
            $table->foreignId('project_id')->constrained('projects')->cascadeOnDelete();
            $table->foreignId('phase_id')->nullable()->constrained('phases')->cascadeOnDelete();
            $table->decimal('latitude', 10, 7)->nullable(); // 26.8206
            $table->decimal('longitude', 10, 7)->nullable(); // 30.8025
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('properties');

    }
};
