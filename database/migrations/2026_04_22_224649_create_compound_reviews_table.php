<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('compound_reviews', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('compound_id')->constrained('compounds')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title')->nullable();
            $table->text('comment')->nullable();
            $table->unsignedTinyInteger('overall_rating');
            $table->unsignedTinyInteger('location_rating')->nullable();
            $table->unsignedTinyInteger('amenities_rating')->nullable();
            $table->unsignedTinyInteger('value_for_money_rating')->nullable();
            $table->unsignedTinyInteger('developer_reputation_rating')->nullable();
            $table->timestamps();

            $table->unique(['compound_id', 'user_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('compound_reviews');
    }
};
