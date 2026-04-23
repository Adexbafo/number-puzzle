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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('farcaster_fid')->nullable()->unique()->after('wallet_address');
            $table->string('farcaster_fname')->nullable()->after('farcaster_fid');
            $table->string('farcaster_pfp')->nullable()->after('farcaster_fname');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['farcaster_fid', 'farcaster_fname', 'farcaster_pfp']);
        });
    }
};
