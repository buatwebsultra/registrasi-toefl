<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->integer('raw_listening_pbt')->nullable()->after('test_date');
            $table->integer('raw_structure_pbt')->nullable()->after('raw_listening_pbt');
            $table->integer('raw_reading_pbt')->nullable()->after('raw_structure_pbt');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('participants', function (Blueprint $table) {
            $table->dropColumn(['raw_listening_pbt', 'raw_structure_pbt', 'raw_reading_pbt']);
        });
    }
};
