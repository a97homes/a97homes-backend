<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('phases', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('compound_id')->constrained('compounds')->cascadeOnDelete();
            $table->json('name');
            $table->json('description')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('completion_status', 30)->default('under_construction');
            $table->unsignedInteger('sort_order')->default(0);
            $table->timestamps();

            $table->index(['compound_id', 'sort_order']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('phases');
    }
};
