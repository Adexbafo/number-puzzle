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
        Schema::create('matches', function (Blueprint $table) {
    $table->id();

    $table->foreignId('player_one')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->foreignId('player_two')
        ->nullable()
        ->constrained('users')
        ->cascadeOnDelete();

    $table->decimal('stake', 18, 8);

    $table->enum('status', [
        'waiting',
        'funded',
        'active',
        'finished',
        'cancelled'
    ])->default('waiting');

    $table->foreignId('winner')
        ->nullable()
        ->constrained('users')
        ->nullOnDelete();

    $table->timestamp('started_at')->nullable();
    $table->timestamp('ended_at')->nullable();

    $table->timestamps();

    $table->index('status');
});
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('matches');
    }
};
