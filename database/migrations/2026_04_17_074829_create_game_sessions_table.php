<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('game_sessions', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')
                ->constrained()
                ->cascadeOnDelete();

            // Restrict valid modes
            $table->enum('mode', ['single', 'multiplayer']);

            // Game progression
            $table->integer('round')->default(1);
            $table->integer('question_number')->default(1);

            // State
            $table->boolean('is_active')->default(true);

            // Timing
            $table->timestamp('started_at')->nullable();
            $table->timestamp('ended_at')->nullable(); // IMPORTANT

            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'is_active']);
            $table->index('mode');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('game_sessions');
    }
};