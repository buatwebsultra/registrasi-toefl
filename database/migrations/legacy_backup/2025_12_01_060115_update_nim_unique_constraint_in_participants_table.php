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
        Schema::table('participants', function (Blueprint $table) {
            // Drop the existing unique constraint on NIM
            $table->dropUnique(['nim']); // This removes the 'participants_nim_unique' constraint

            // For SQLite, we need to handle partial unique constraint differently
            // Since SQLite doesn't directly support partial unique constraints like other DB engines,
            // we'll rely on our application-level validation which checks for non-deleted records only
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            // Add back the unique constraint on NIM (this will prevent any duplicate NIMs)
            $table->unique('nim');
        });
    }
};
