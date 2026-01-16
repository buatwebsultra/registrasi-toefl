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
        // Drop unique index for username if it exists to allow multiple records per student for history
        try {
            Schema::table('participants', function (Blueprint $table) {
                $table->dropUnique(['username']);
            });
        } catch (\Exception $e) {
            // Ignore if the index doesn't exist (common on fresh installations)
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->unique('username');
        });
    }
};
