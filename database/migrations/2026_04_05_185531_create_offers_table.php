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
        Schema::create('offers', function (Blueprint $table) {
            $table->id();
            $table->foreignId('compound_id')->constrained('compounds')->cascadeOnDelete();
            $table->unsignedInteger('installment_years')->nullable();
            $table->decimal('down_payment_percentage', 5, 2)->nullable();
            $table->unsignedBigInteger('monthly_payment')->nullable();
            $table->json('description')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->index(['compound_id', 'is_active']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('offers');
    }
};
