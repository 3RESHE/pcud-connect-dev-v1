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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // User who performed the action
            $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();

            // What happened
            $table->string('action'); // e.g., "created", "updated", "approved", "deleted"
            $table->text('description')->nullable(); // Detailed description of the action

            // What was affected (polymorphic)
            $table->string('subject_type')->nullable(); // e.g., "App\Models\JobPosting"
            $table->unsignedBigInteger('subject_id')->nullable(); // e.g., job posting ID

            // Additional data
            $table->json('properties')->nullable(); // Changed fields, old values, new values, etc.

            // Request context
            $table->string('ip_address', 45)->nullable(); // IPv4 or IPv6
            $table->text('user_agent')->nullable(); // Browser/client info

            $table->timestamp('created_at')->useCurrent();
        });

        // Add indexes
        Schema::table('activity_logs', function (Blueprint $table) {
            $table->index('user_id', 'idx_activity_logs_user');
            $table->index(['subject_type', 'subject_id'], 'idx_activity_logs_subject');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
