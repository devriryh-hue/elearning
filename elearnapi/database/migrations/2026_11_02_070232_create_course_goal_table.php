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
        Schema::create('course_goals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('goal_id')->constrained()->cascadeOnDelete();
            $table->foreignId('course_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('required_attempts')->default(3);  // tentatives par leçon
            $table->unsignedTinyInteger('target_percent')->default(80);    // score cible
            $table->unsignedTinyInteger('weight')->default(1);             // importance dans l'objectif
            $table->timestamps();
            $table->unique(['goal_id', 'course_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('course_goal');
    }
};
