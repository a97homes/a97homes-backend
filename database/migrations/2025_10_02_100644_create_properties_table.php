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
            $table->decimal('price', 15, 2)->nullable();
            $table->decimal('resale_price', 15, 2)->nullable();
            $table->foreignId('compound_id')->constrained('compounds')->cascadeOnDelete();
            $table->decimal('latitude', 10, 7)->nullable();
            $table->decimal('longitude', 10, 7)->nullable();
            $table->timestamps();

            $table->index('price');
            $table->index(['compound_id', 'price']);
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
