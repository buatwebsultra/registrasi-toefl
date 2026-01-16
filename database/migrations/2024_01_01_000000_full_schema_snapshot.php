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
        // 1. Users Table
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();
            $table->timestamps();
            $table->string('role')->default('user');
        });

        // 2. Password Reset Tokens
        Schema::create('password_reset_tokens', function (Blueprint $table) {
            $table->string('email')->primary();
            $table->string('token');
            $table->timestamp('created_at')->nullable();
        });

        // 3. Failed Jobs
        Schema::create('failed_jobs', function (Blueprint $table) {
            $table->id();
            $table->string('uuid')->unique();
            $table->text('connection');
            $table->text('queue');
            $table->longText('payload');
            $table->longText('exception');
            $table->timestamp('failed_at')->useCurrent();
        });

        // 4. Faculties
        Schema::create('faculties', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // 5. Study Programs
        Schema::create('study_programs', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('level'); // e.g., S1, D3
            $table->foreignId('faculty_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });

        // 6. Schedules
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->date('date');
            $table->string('room');
            $table->integer('capacity');
            $table->integer('used_capacity')->default(0);
            $table->enum('status', ['available', 'full'])->default('available');
            $table->string('category'); // ITP, Prediction, etc.
            $table->time('time')->default('08:00:00');
            $table->string('signature_name')->nullable();
            $table->string('signature_nip')->nullable();
            $table->timestamps();
        });

        // 7. Participants
        Schema::create('participants', function (Blueprint $table) {
            $table->id();
            $table->foreignId('schedule_id')->constrained()->onDelete('cascade');
            $table->foreignId('study_program_id')->nullable()->constrained()->onDelete('set null');
            $table->foreignId('faculty_id')->nullable()->constrained()->onDelete('set null');
            
            $table->string('seat_number')->nullable();
            $table->string('status')->default('pending'); // pending, verified, rejected
            $table->string('seat_status')->default('reserved');

            $table->string('nim');
            $table->string('name');
            $table->string('gender'); // L/P
            $table->string('birth_place');
            $table->date('birth_date');
            $table->string('email');
            $table->string('major'); // Assuming redundant with study_program implementation or legacy string
            $table->string('faculty'); // Assuming redundant or legacy string
            $table->string('phone');
            
            $table->date('payment_date');
            $table->string('test_category');
            $table->date('previous_test_date')->nullable();
            
            // File Paths
            $table->string('payment_proof_path');
            $table->string('photo_path');
            $table->string('ktp_path');
            
            // Login credentials generated
            $table->string('username')->nullable();
            $table->string('password')->nullable();
            
            // Results
            $table->decimal('test_score', 5, 2)->nullable();
            $table->boolean('passed')->default(false);
            $table->date('test_date')->nullable();
            
            // Breakdown scores
            $table->decimal('reading_score', 4, 1)->nullable();
            $table->decimal('listening_score', 4, 1)->nullable();
            $table->decimal('speaking_score', 4, 1)->nullable();
            $table->decimal('writing_score', 4, 1)->nullable();
            $table->string('test_format')->default('iBT'); // iBT or PBT
            
            // PBT specific
            $table->integer('listening_score_pbt')->nullable();
            $table->integer('structure_score_pbt')->nullable();
            $table->integer('reading_score_pbt')->nullable();
            $table->integer('total_score_pbt')->nullable();
            
            $table->string('academic_level')->nullable();
            $table->string('temp_seat_number')->nullable();
            $table->string('rejection_message')->nullable();
            
            $table->string('attendance')->nullable(); // present, absent, permission
            $table->dateTime('attendance_marked_at')->nullable();
            $table->string('verification_token')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
        
        // Standard Laravel Tables
        Schema::create('sessions', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });

        Schema::create('cache', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->mediumText('value');
            $table->integer('expiration');
        });

        Schema::create('cache_locks', function (Blueprint $table) {
            $table->string('key')->primary();
            $table->string('owner');
            $table->integer('expiration');
        });

        Schema::create('jobs', function (Blueprint $table) {
            $table->id();
            $table->string('queue')->index();
            $table->longText('payload');
            $table->unsignedTinyInteger('attempts');
            $table->unsignedInteger('reserved_at')->nullable();
            $table->unsignedInteger('available_at');
            $table->unsignedInteger('created_at');
        });

        Schema::create('job_batches', function (Blueprint $table) {
            $table->string('id')->primary();
            $table->string('name');
            $table->integer('total_jobs');
            $table->integer('pending_jobs');
            $table->integer('failed_jobs');
            $table->longText('failed_job_ids');
            $table->mediumText('options')->nullable();
            $table->integer('cancelled_at')->nullable();
            $table->integer('created_at');
            $table->integer('finished_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('participants');
        Schema::dropIfExists('schedules');
        Schema::dropIfExists('study_programs');
        Schema::dropIfExists('faculties');
        Schema::dropIfExists('failed_jobs');
        Schema::dropIfExists('password_reset_tokens');
        Schema::dropIfExists('users');
        Schema::dropIfExists('sessions');
        Schema::dropIfExists('cache');
        Schema::dropIfExists('cache_locks');
        Schema::dropIfExists('jobs');
        Schema::dropIfExists('job_batches');
    }
};
