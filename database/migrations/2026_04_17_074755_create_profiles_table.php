<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            // One profile per user
            $table->foreignId('user_id')
                ->unique() // IMPORTANT: prevents duplicates
                ->constrained()
                ->cascadeOnDelete();

            // Game state
            $table->enum('level', ['amateur', 'professional'])
                ->default('amateur');

            $table->integer('lifelines')->default(5);
            $table->integer('round')->default(1);
            $table->integer('score')->default(0);

            // Web3
            $table->string('wallet_address')->nullable();

            $table->timestamps();

            // Optional indexes (performance)
            $table->index('score');
            $table->index('level');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('profiles');
    }
};