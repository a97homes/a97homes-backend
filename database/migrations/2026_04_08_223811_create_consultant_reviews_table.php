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
        Schema::create('consultant_reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('consultant_id')->constrained('consultants')->cascadeOnDelete();
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('title');
            $table->text('comment');
            $table->unsignedTinyInteger('overall_rating');
            $table->unsignedTinyInteger('local_knowledge_rating')->nullable();
            $table->unsignedTinyInteger('process_expertise_rating')->nullable();
            $table->unsignedTinyInteger('response_speed_rating')->nullable();
            $table->unsignedTinyInteger('negotiation_skills_rating')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('consultant_reviews');
    }
};
